<?php

namespace App\Controllers\Api;

use App\Models\StudentModel;
use CodeIgniter\Files\Exceptions\FileNotFoundException;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\Validation\Exceptions\ValidationException;

class ApiController extends ResourceController
{
    public function addStudent()
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|valid_email|is_unique[students.email]'
        ];

        if (!$this->validate($rules)) {
            $errors = $this->validator->getErrors();
            return $this->response->setStatusCode(400)->setJSON([
                'status_code' => '400 Bad Request',
                'message' => 'Validation Errors',
                'errors' => $errors
            ]);
        } else {
            $validatedData = $this->validator->getValidated();
            $file = $this->request->getFile('profile_image') ?? null;
            if (isset($file) && !empty($file)) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $imageArr = explode('.', $file->getName());

                    $imageName = time() . '.' . end($imageArr);
                    $file->move('images', $imageName);
                    $validatedData['profile_image'] = $imageName;
                } else {
                    return $this->response->setStatusCode(400)->setJSON([
                        'status_code' => '400 Bad Request',
                        'message' => 'File upload failed or file not found'
                    ]);
                }
            }

            if ($this->request->getVar('phone')) {
                $phone = $this->request->getVar('phone');
                $validatedData['phone'] = $phone;
            }

            $model = model(StudentModel::class);
            $model->insert($validatedData);
            return $this->response->setStatusCode(200)->setJSON([
                'status_code' => '200 OK',
                'message' => 'Student Added Successfully'
            ]);
        }
    }

    public function listStudents()
    {
        $users = model(StudentModel::class)->findAll();

        if (!isset($users) || empty($users)) {
            $response = [
                'status' => '404',
                'message' => 'Something Went wrong',
                'data' => []
            ];
        } else {
            $response = [
                'status' => '200 ok',
                'message' => 'Success',
                'data' => $users
            ];
        }

        return $this->respondCreated($response);
    }

    public function singleStudentData($student_id)
    {
    }

    public function updateStudent($student_id)
    {
    }

    public function deleteStudent($student_id)
    {
    }
}
