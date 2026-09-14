<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

/**
 * Perfil do usuário do painel: nome, e-mail, foto e senha.
 *
 * A foto fica em storage/app/avatars, FORA da raiz pública, e é servida pela
 * rota perfil.avatar. Guardar upload em pasta pública é como se executa código
 * no servidor por acidente; assim o arquivo nunca é alcançado direto.
 */
class PerfilController extends Controller
{
    /** Pasta das fotos, dentro de storage/app. */
    private const PASTA = 'avatars';

    /** Tamanho máximo da foto, em KB (2 MB). */
    private const FOTO_KB = 2048;

    public function edit()
    {
        return view('perfil.edit', ['user' => Auth::user()]);
    }

    /** Nome, e-mail e foto. */
    public function update(Request $request)
    {
        $user = Auth::user();

        $dados = $request->validate([
            'name'   => 'required|string|max:120',
            'email'  => ['required', 'email', 'max:180',
                         Rule::unique('users', 'email')->ignore($user->id)],
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:' . self::FOTO_KB,
            // Só é exigida quando o e-mail muda: o e-mail é o login.
            'senha_atual_perfil' => 'nullable|string',
        ], [], [
            'name'   => 'nome',
            'email'  => 'e-mail',
            'avatar' => 'foto',
        ]);

        $trocandoEmail = strtolower(trim($dados['email'])) !== strtolower($user->email);

        if ($trocandoEmail && ! Hash::check((string) $request->input('senha_atual_perfil'), $user->password)) {
            return back()
                ->withErrors(['senha_atual_perfil' => 'Para mudar o e-mail, informe a senha atual.'])
                ->withInput();
        }

        $user->name  = $dados['name'];
        $user->email = $dados['email'];

        if ($request->hasFile('avatar')) {
            $antiga = $user->avatar;
            $user->avatar = $request->file('avatar')->store(self::PASTA, 'local');

            // Apaga a anterior só depois de guardar a nova, para não ficar sem
            // nenhuma se a gravação falhar.
            if ($antiga && $antiga !== $user->avatar) {
                Storage::disk('local')->delete($antiga);
            }

            // Deixa sempre quadrada e quadrada de verdade. A tela já manda o
            // recorte pronto, mas se o navegador não conseguir aplicar o
            // ajuste, isso evita que a foto chegue torta e seja cortada no
            // meio pela moldura redonda.
            // O caminho pode mudar (PNG vira JPG), então o retorno vai para o
            // banco.
            $user->avatar = $this->quadrar($user->avatar);
        }

        $user->save();

        return redirect()->route('perfil.edit')
            ->with('success', 'Perfil atualizado.');
    }

    /** Troca de senha. */
    public function senha(Request $request)
    {
        $user = Auth::user();

        $dados = $request->validate([
            'senha_atual' => 'required|string',
            'password'    => 'required|string|min:8|confirmed',
        ], [], [
            'senha_atual' => 'senha atual',
            'password'    => 'nova senha',
        ]);

        if (! Hash::check($dados['senha_atual'], $user->password)) {
            return back()
                ->withErrors(['senha_atual' => 'A senha atual não confere.'])
                ->withInput();
        }

        if (Hash::check($dados['password'], $user->password)) {
            return back()
                ->withErrors(['password' => 'A nova senha precisa ser diferente da atual.'])
                ->withInput();
        }

        $user->password = Hash::make($dados['password']);
        $user->save();

        // Renova o id da sessão: quem tiver um cookie antigo deixa de valer.
        $request->session()->regenerate();

        return redirect()->route('perfil.edit')
            ->with('success', 'Senha alterada.');
    }

    /** Remove a foto, voltando para as iniciais. */
    public function removerAvatar()
    {
        $user = Auth::user();

        if ($user->avatar) {
            Storage::disk('local')->delete($user->avatar);
            $user->avatar = null;
            $user->save();
        }

        return redirect()->route('perfil.edit')->with('success', 'Foto removida.');
    }

    /**
     * Recorta a foto em quadrado e reduz para 400x400, gravando como JPEG.
     *
     * Devolve o caminho final: o nome do arquivo muda de extensão quando a
     * origem não é JPEG, então quem chamou precisa gravar esse retorno no
     * banco - senão o avatar apontaria para um arquivo já apagado.
     *
     * A tela de perfil já envia o recorte escolhido pelo usuário. Isto aqui é a
     * garantia do lado do servidor: qualquer imagem que chegue - inclusive de
     * navegador que não conseguiu aplicar o ajuste - termina quadrada, então a
     * moldura circular do painel nunca corta a foto no meio.
     *
     * Falha em silêncio de propósito: sem GD, a foto original continua valendo
     * em vez de a atualização dar erro.
     */
    private function quadrar(string $caminho): string
    {
        if (! function_exists('imagecreatefromstring')) {
            return $caminho;
        }

        $disco = Storage::disk('local');
        $bruto = @file_get_contents($disco->path($caminho));
        if ($bruto === false) {
            return $caminho;
        }

        $origem = @imagecreatefromstring($bruto);
        if (! $origem) {
            return $caminho;
        }

        $lado = 400;
        $w = imagesx($origem);
        $h = imagesy($origem);
        $menor = min($w, $h);

        $destino = imagecreatetruecolor($lado, $lado);
        // Fundo branco: foto PNG com transparência viraria preto no JPEG.
        imagefilledrectangle($destino, 0, 0, $lado, $lado, imagecolorallocate($destino, 255, 255, 255));

        // Recorte central quando a imagem não vem quadrada
        imagecopyresampled(
            $destino, $origem,
            0, 0,
            (int) (($w - $menor) / 2), (int) (($h - $menor) / 2),
            $lado, $lado,
            $menor, $menor
        );

        $nome     = pathinfo($caminho, PATHINFO_FILENAME) . '.jpg';
        $relativo = self::PASTA . '/' . $nome;
        $gravou   = @imagejpeg($destino, $disco->path($relativo), 90);

        imagedestroy($origem);
        imagedestroy($destino);

        if (! $gravou) {
            return $caminho;
        }

        // Só apaga o original depois de o JPEG estar gravado, e só se o nome
        // tiver mudado (fonte JPEG gera o mesmo nome).
        if ($relativo !== $caminho) {
            $disco->delete($caminho);
        }

        return $relativo;
    }

    /**
     * Serve a foto do usuário logado.
     *
     * O caminho vem do banco, nunca da URL: assim não existe como pedir outro
     * arquivo do servidor pela rota.
     */
    public function avatar()
    {
        $user = Auth::user();

        abort_if(! $user->avatar, 404);
        abort_unless(Storage::disk('local')->exists($user->avatar), 404);

        // A URL é a mesma para todo mundo (/perfil/avatar), então um cache
        // COMPARTILHADO poderia guardar a foto de um usuário e entregá-la a
        // outro. setPrivate() marca como cache de navegador apenas. Passar
        // 'private' pelo array de cabeçalhos não basta: o Symfony troca por
        // 'public' ao preparar a resposta.
        $resp = response()->file(Storage::disk('local')->path($user->avatar));
        $resp->setPrivate();
        $resp->setMaxAge(604800);

        return $resp;
    }
}
