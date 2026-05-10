<?php 
  
namespace App\Http\Controllers; 
  
use Illuminate\Http\Request; 
use PragmaRX\Google2FA\Google2FA; 
use BaconQrCode\Renderer\ImageRenderer; 
use BaconQrCode\Renderer\Image\SvgImageBackEnd; 
use BaconQrCode\Renderer\RendererStyle\RendererStyle; 
use BaconQrCode\Writer; 
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
  
class TwoFactorController extends Controller 
{ 
    // Muestra la página de configuración 2FA con QR 
    public function show(Request $request)
     { 
        $user = $request->user(); 
        $google2fa = new Google2FA(); 
  
        // Si el usuario aún no tiene secreto, lo generamos y guardamos 
        if (!$user->two_factor_secret) { 
            $secret = $google2fa->generateSecretKey(); 
            $user->update(['two_factor_secret' => $secret]); 
        } 
  
        // Construimos la URL otpauth:// que el QR codifica 
        $qrCodeUrl = $google2fa->getQRCodeUrl( 
            config('app.name'), 
            $user->email, 
            $user->two_factor_secret 
        ); 
  
        // Renderizamos el QR como SVG (sin dependencias nativas) 
        $renderer = new ImageRenderer( 
            new RendererStyle(200), 
            new SvgImageBackEnd() 
        ); 
        $writer = new Writer($renderer); 
        $qrCodeSvg = $writer->writeString($qrCodeUrl); 
  
        return view('two-factor.setup', [ 
            'qrCodeSvg' => $qrCodeSvg, 
            'secret'    => $user->two_factor_secret, 
            'enabled'   => $user->two_factor_enabled, 
        ]); 
    } 
    // Activa 2FA después de verificar el código 
   public function enable(Request $request) 
    { 
        $request->validate(['code' => 'required|string']); 

        $user = $request->user(); 
        $google2fa = new Google2FA(); 

        $valid = $google2fa->verifyKey($user->two_factor_secret, $request->code, 4); 

        if ($valid) {
            $recoveryCodes = [];
            $plainCodes = [];

            for ($i = 0; $i < 8; $i++) {
                $code = Str::random(10);
                $plainCodes[] = $code;
                $recoveryCodes[] = Hash::make($code);
            }

            $user->update([
                'two_factor_enabled' => true,
                'two_factor_recovery_codes' => $recoveryCodes,
            ]);

            return redirect()->route('two-factor.setup')
                ->with('status', '2FA activado correctamente.')
                ->with('recovery_codes', $plainCodes);
        }

        // CRÍTICO: Si el código es inválido, debe regresar con error. 
        // Sin esto, sale la pantalla blanca.
        return back()->withErrors(['code' => 'El código OTP es inválido o ha expirado.']);
    }
  
    // Desactiva 2FA 
    public function disable(Request $request)
     { 
        $request->user()->update([ 
            'two_factor_enabled' => false, 
            'two_factor_secret'  => null, 
        ]); 
  
        return redirect()->route('two-factor.setup') 
            ->with('status', '2FA desactivado.'); 
    } 

    public function verifyRecovery(Request $request)
{
    $request->validate(['recovery_code' => 'required|string']);
    $user = $request->user();
    $codes = $user->two_factor_recovery_codes ?? [];

    foreach ($codes as $key => $hashedCode) {
        if (Hash::check($request->recovery_code, $hashedCode)) {
            // Código válido: Lo eliminamos para que sea de UN SOLO USO
            unset($codes[$key]);
            $user->update(['two_factor_recovery_codes' => array_values($codes)]);
            
            // Registramos la verificación en la sesión
            session(['two_factor_verified' => true]);
            
            return redirect()->route('dashboard');
        }
    }

    return back()->withErrors(['recovery_code' => 'El código de respaldo es inválido o ya fue usado.']);
}
} 