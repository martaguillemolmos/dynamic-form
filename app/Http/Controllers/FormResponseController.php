<?php
namespace App\Http\Controllers;
use App\Models\FormResponse;
use App\Models\FormTemplate;
use Illuminate\Http\Request;
use MongoDB\BSON\ObjectId;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class FormResponseController extends Controller
{
public function store(Request $request, $formId)
{
    try {
        // Buscar la plantilla del formulario
        $formTemplate = FormTemplate::find($formId);

        if (!$formTemplate) {
            return response()->json(['error' => 'Formulario no encontrado'], 404);
        }

        // Validaciones dinámicas basadas en los campos del formulario en bbdd
        $rules = [];
        foreach ($formTemplate->fields as $field) {
            $rules[$field['name']] = 'required|string';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Extraer solo los campos definidos en el formulario
        $responseData = $request->only(array_keys($rules));
        $responseData['form_id'] = new ObjectId($formId);
        $responseData['created_at'] = now();

        // Configurar la conexión directa con MongoDB
        $mongoClient = new \MongoDB\Client(
            env('MONGODB_URI', 'mongodb://localhost:27017')
        );
        
        $database = env('MONGODB_DATABASE', 'form');
        $collection = $mongoClient->$database->form_responses;

        // Insertar documento en la colección 
        $insertResult = $collection->insertOne($responseData);

        if ($insertResult->getInsertedCount() === 1) {
            return response()->json([
                'message' => 'Respuesta guardada correctamente',
                'inserted_id' => (string)$insertResult->getInsertedId()
            ]);
        } else {
            return response()->json(['error' => 'No se pudo guardar la respuesta'], 500);
        }

    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Error al guardar la respuesta',
            'message' => $e->getMessage()
        ], 500);
    }
}
}