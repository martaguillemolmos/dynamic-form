<?php

namespace Database\Seeders;

use App\Models\FormTemplate;
use Illuminate\Database\Seeder;

class FormTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FormTemplate::create([
            'name' => 'Newsletter',
            'fields' => [
                [
                    'label' => 'name', 
                    'name' => 'Nombre', 
                    'type' => 'text',
                    'required' => true 
                ],
                [
                    'label' => 'email', 
                    'name' => 'Correo electrónico', 
                    'type' => 'email',
                    'required' => true
                ],
                [
                    'label' => 'subscription', 
                    'name' => '¿Desea recibir correos?', 
                    'type' => 'select', 
                    'options' => ['sí', 'no'],
                    'default' => 'si',
                    'required' => false

                ],
                [
                    'label' => 'comments', 
                    'name' => 'Comentarios adicionales', 
                    'type' => 'textarea',
                    'required' => false
                ]
            ]
        ]);
    }
}