## Instalar o laravel 11

comando composer create-project laravel/laravel . "11.\*"

## Configurar banco de dados

Configuração é feita no arquivo .env

## Migration

php artisan migrate

## Criar arquivo API

php artisan install:api

## Controller

php artisan make:controller UserController

## Seeder

Usar seed com comando:

php artisan make:seed UserSeeder

php artisan db:seed --class=DatabaseSeeder

## Gerar um apiKey

php artisan key:generate

## Request

php artisan make:request UserRequest

class UserRequest extends FormRequest
{
/\*\*
_ Determine if the user is authorized to make this request.
_/
public function authorize(): bool
{
return true;
}

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status'=> false,
            'erros'=> $validator->errors(),
        ],422));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = $this->route('user');

        return [
            "name"=> 'required',
            "email"=> 'requerid|email|unique:users,email' . ($userId ? $userId->id : null),
            "password"=> 'riquered|min:6'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'=>'O campo nome é obrigatótio',
            'email.required'=>'O campo email é obrigatório',
            'email.email'=>'O email deve ser valido',
            'email.unique'=>'O email já está cadastrado',
            'password.required'=>'O campo senha é obrigatório',
            'password.min'=>'Sua senha deve ter no minimo 6 caracteres'
        ];
    }

}

## Rotas

-Listar:

\*Usado para teste
Route::get('/user', function (Request $request) {
return response()->json([
'status' => true,
'message' => 'Listar usuários!'
],200);
});

Route::get('/users', [UserController::class, 'index']);

http://127.0.0.1:8000/api/user

public function index()
{
$user = User::orderBy('id', 'DESC')->get();
return response()->json([
'status' => true,
'usuário' => $user
],200);
}

-Show:

public function show(User $id)
{
return response()->json([
'status' => true,
'usuário' => $id
],200);
}

Route::get('users/{id}', [UserController::class, 'show']);

http://127.0.0.1:8000/api/users/1

-Criar/Store:

public function store(Request $request)
{
DB::beginTransaction();

        try{
            User::create([
                "name" => $request->name,
                "email" => $request->email,
                "password" => $request->password
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => "Usuário cadastrado com sucesso!",
            ],201);

        }catch (Exception $e){
            DB::rollback();

            return response()->json([
                'status' => false,
                'message' => "Erro ao cadastrar usuário!",
                'erro' => $e
            ],400);
        }
    }

    http://127.0.0.1:8000/api/users

\*Exemplo:
{
"name": "Teste",
"email": "teste@gmail.com",
"password": "123456"
}

-Update:

Route::put('/users/{id}', [UserController::class, 'update']);

public function update(UserRequest $request, User $id)
{

        DB::beginTransaction();

        try{

            $id->update([
                "name" => $request->name,
                "email" => $request->email,
                "password" => $request->password
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => "Usuário editado com sucesso!",
            ],200);

        } catch (Exception $e) {
            DB::rollback();

            return response()->json([
                'status' => false,
                'message' => "Erro não editado!",
                'erro' => $e
            ],400);

        }

        return response()->json([
            'status' => true,
            'usuario' => $id,
            'message' => "Usuário editado com sucesso!",
        ],200);
    }

-Destroy:

public function destroy(User $id)
{
try{

            $id->delete();

            return response()->json([
                'status' => true,
                'message' => "Usuário exluido com sucesso!",
            ],200);

        } catch(Exception $e){
            DB::rollback();

            return response()->json([
                'status' => false,
                'message' => "Erro ao apagar usuário!",
                'erro' => $e
            ],400);

        }
    }
