<?php
namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Create User
     */
    public function createUser(Request $request)
    {
        try {
            // Validate user input
            $validateUser = Validator::make($request->all(), [
                'nom' => 'required|string|max:255',
                // 'prenom' => 'string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email',
                'numero' => 'required|string|unique:users,numero',
                'password' => 'required|string|min:8|confirmed', // Ensure the password is confirmed
            ]);

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validateUser->errors()
                ], 422);
            }

            // Create user
            $user = User::create([
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'email' => $request->email,
                'numero' => $request->numero,
                'password' => $request->password, // Will be hashed in the model's mutator
            ]);

            return response()->json([
                'status' => true,
                'message' => 'User Created Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Login The User
     */
    public function loginUser(Request $request)
    {
        try {
            // Validate login data
            $validateUser = Validator::make($request->all(), [
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validateUser->errors()
                ], 422);
            }

            // Attempt to authenticate the user
            if (!Auth::attempt($request->only('email', 'password'))) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email & Password do not match our records.',
                ], 401);
            }

            // Get the authenticated user
            $user = User::where('email', $request->email)->first();

            // Update last login time
            $user->update(['last_login_at' => Carbon::now()]);

            return response()->json([
                'status' => true,
                'firstname' => $user->prenom,
                'name' => $user->nom,
                'numero' => $user->numero,
                'email' => $user->email,
                'image'=>$user->profil_image,
                'message' => 'User Logged In Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Logout The User
     */
    public function logout(Request $request)
    {
        try {
            $user = $request->user();
            $user->currentAccessToken()->delete();

            return response()->json([
                'status' => true,
                'message' => 'User Logged Out Successfully'
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Drop The User
     */
    public function dropUserInTable(Request $request)
    {
        try {
            $id = $request->id;
            $user = User::find($id);

            if ($user) {
                // Utilisateur connecté effectue la suppression
                $deletedBy = auth()->user();
                $user->delete(); // Soft delete

                return response()->json([
                    'status' => true,
                    'message' => "Suppression effectuée par " . $deletedBy->nom
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => "Utilisateur non trouvé."
                ], 404);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Update Profile Image
     */
    public function updateProfileImage(Request $request)
    {
        $user = auth()->user();
    
        // Validation de l'image
        $request->validate([
            'profil_image' => 'required|image|mimes:jpeg,png,jpg,gif', // Exemple de validation
        ]);
    
        if ($request->hasFile('profil_image')) {
            // Suppression de l'ancienne image si elle existe
            if ($user->profil_image) {
                Storage::disk('public')->delete($user->profil_image);
            }
    
            // Stockage de la nouvelle image
            $path = $request->file('profil_image')->store('profil_images', 'public');
            $user->profil_image = $path;
            $user->save();
    
            return response()->json(['message' => 'Profile image updated successfully']);
        }
    
        return response()->json(['message' => 'No image provided'], 400);
    }
    

    // Other profile update methods remain unchanged...

    public function updateName(Request $request)
    {
        try {
            $user = auth()->user();
            $user->nom = $request->input('name');
            // $user->prenom = $request->input('prenom');
            $user->save();
            return response()->json(['message' => 'Name updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update name', 'message' => $e->getMessage()], 500);
        }
    }

    public function updateEmail(Request $request)
    {
        $user = auth()->user();
        $user->email = $request->input('email');
        $user->save();
        return response()->json(['message' => 'Email updated successfully']);
    }

    public function updateNumber(Request $request)
    {
        $user = auth()->user();
        $user->numero = $request->input('numero');
        $user->save();
        return response()->json(['message' => 'Number updated successfully']);
    }

    public function sendOtp(Request $request)
    {
        $user = auth()->user();
        $otp = random_int(1000, 9999); // Générer un code OTP aléatoire
        $user->otp = $otp;
        $user->otp_expires_at = now()->addMinutes(10); // Définir une expiration de 10 minutes
        $user->save();

        // Envoyer le code OTP par email
        Mail::send('emails.otp', ['otp' => $otp], function ($message) use ($user) {
            $message->to($user->email)->subject('Votre code OTP');
        });

        return response()->json(['message' => 'OTP sent successfully']);
    }

    public function sendOtpMdp(Request $request)
    {
        $validateUser = Validator::make($request->all(), [
            'user' => 'required|exists:users,email',
        ]);

        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => "Ce mail n'a pas de compte",
                'errors' => $validateUser->errors()
            ], 422);
        }

        // Fetch the user from the database
        $user = User::where('email', $request->user)->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => "Utilisateur non trouvé",
            ], 404);
        }

        $otp = random_int(1000, 9999); // Générer un code OTP aléatoire
        $user->otp = $otp;
        $user->otp_expires_at = Carbon::now()->addMinutes(10); // Définir une expiration de 10 minutes
        $user->save();

        // Envoyer le code OTP par email
        Mail::send('emails.otp', ['otp' => $otp], function ($message) use ($user) {
            $message->to($user->email)->subject('Votre code OTP');
        });

        return response()->json(['message' => 'OTP sent successfully']);
    }

    public function verifyOtp(Request $request)
    {
        $validateUser = Validator::make($request->all(), [
            'user' => 'required|exists:users,email',
        ]);

        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => "Ce mail n'a pas de compte",
                'errors' => $validateUser->errors()
            ], 422);
        }

        $user = User::where('email', $request->user)->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => "Utilisateur non trouvé",
            ], 404);
        }

        if ($user->otp === $request->input('otp') && $user->otp_expires_at > now()) {
            // Le code OTP est correct et n'a pas expiré
            $user->otp = null;
            $user->otp_expires_at = null;
            $user->save();
            return response()->json(['message' => 'OTP verified successfully','id'=>$user->id]);
        }
        return response()->json(['message' => 'Invalid or expired OTP'], 400);
    }

    public function verifyOtpCompte(Request $request)
    {
        $validateUser = Validator::make($request->all(), [
            'user' => 'required|exists:users,email',
            'otp' => 'required|string',
        ]);

        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => "Validation failed",
                'errors' => $validateUser->errors()
            ], 422);
        }

        $user = User::where('email', $request->user)->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => "Utilisateur non trouvé",
            ], 404);
        }

        if ($user->otp === $request->input('otp') && $user->otp_expires_at > now()) {
            // Le code OTP est correct et n'a pas expiré
            $user->otp = null;
            $user->otp_expires_at = null;
            $user->email_verified_at = now();
            $user->save();

            return response()->json([
                'status' => true,
                'message' => 'OTP verified successfully',
                'id' => $user->id
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Invalid or expired OTP'
        ], 400);
    }

    public function updatePassword(Request $request)
    {
        $user = auth()->user();
        if (Hash::check($request->input('old_password'), $user->password)) {
            $user->updateProfile([
                'password' => $request->input('new_password'),
            ]);
            return response()->json(['message' => 'Password updated successfully']);
        }
        return response()->json(['message' => 'Incorrect old password'], 400);
    }

    public function updatePasswordMdp(Request $request)
    {
        $validateUser = Validator::make($request->all(), [
            'email' => 'required|exists:users,email',
            'new_password' => 'required|min:8', // Add any additional password rules here
        ]);

        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => "Validation failed",
                'errors' => $validateUser->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => "Utilisateur non trouvé",
            ], 404);
        }

        $user->password = $request->new_password;
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Password updated successfully'
        ]);
    }

    /**
     * Social Login (Google/Apple)
     */
    public function socialLogin(Request $request)
    {
        try {
            // Vérifier d'abord si l'utilisateur existe déjà
            $existingUser = User::where('email', $request->email)->first();

            // Si l'utilisateur n'existe pas, le numéro est obligatoire
            $rules = [
                'provider' => 'required|string|in:google,apple',
                'token' => 'required|string',
                'email' => 'required|email',
                'name' => 'required|string',
            ];

            if (!$existingUser) {
                $rules['numero'] = 'required|string|unique:users,numero';
            }

            $validateUser = Validator::make($request->all(), $rules);

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validateUser->errors()
                ], 422);
            }

            // Chercher ou créer l'utilisateur
            $user = $existingUser;

            if (!$user) {
                // Créer un nouvel utilisateur
                $user = User::create([
                    'nom' => $request->name,
                    'email' => $request->email,
                    'numero' => $request->numero,
                    'password' => Hash::make(\Illuminate\Support\Str::random(16)),
                    'provider' => $request->provider,
                    'email_verified_at' => now(),
                ]);
            }

            // Mettre à jour le provider si nécessaire
            if (!$user->provider) {
                $user->provider = $request->provider;
                $user->save();
            }

            // Mettre à jour last_login_at
            $user->update(['last_login_at' => Carbon::now()]);

            return response()->json([
                'status' => true,
                'message' => 'Social login successful',
                'token' => $user->createToken("API TOKEN")->plainTextToken,
                'name' => $user->nom,
                'email' => $user->email,
                'numero' => $user->numero ?? '',
                'image' => $user->profil_image ?? '',
            ], 200);

        } catch (\Throwable $th) {
            \Log::error('Social login error: ' . $th->getMessage(), [
                'email' => $request->email ?? null,
                'provider' => $request->provider ?? null,
                'trace' => $th->getTraceAsString()
            ]);
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Mettre à jour le token FCM de l'utilisateur
     */
    public function updateFcmToken(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'fcm_token' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = $request->user();
            $user->fcm_token = $request->fcm_token;
            $user->save();

            return response()->json([
                'status' => true,
                'message' => 'Token FCM mis à jour avec succès'
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
