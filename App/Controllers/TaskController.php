<?php

namespace App\Controllers;

use App\Models\Task;
use Core\Controller;
use Core\View;
use Rakit\Validation\Validator;


class TaskController extends Controller
{
    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function indexAction()
    {
        $validator = new Validator;

        $validation = $validator->make($_GET, [
            'page'  => 'integer',
            'sort_by' => 'in:name,email,id,status',
            'order' => 'in:asc,desc'
        ]);

        $validation->validate();

        if ($validation->fails()) {
            return header('Location: /404');
        }

        $page = $_GET['page'] ?? 1;
        $sort_by = $_GET['sort_by'] ?? 'id';
        $order = $_GET['order'] ?? 'asc';

        $tasks = Task::query()
            ->select('id', 'name', 'text', 'email', 'status', 'is_edited')
            ->orderBy($sort_by, $order)
            ->paginate(3,
                ['*'],
                'page',
                $page
            )->appends(['sort_by' => $sort_by, 'order' => $order]);

        if((int) $page > $tasks->lastPage()) header("Location: /404");

        View::renderTemplate('Task/index.html', compact('tasks', 'sort_by', 'order'));
    }


    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function addAction()
    {
        View::renderTemplate('Task/add.html');
    }


    /**
     * Store New Tasks
     */
    public function storeAction()
    {
        $validator = new Validator;

        $validation = $validator->make($_POST, [
            'name'  => 'required|max:100',
            'email' => 'required|email',
            'text'  => 'required|max:500',
        ]);

        $validation->validate();

        if ($validation->fails()) {
            $errors = $validation->errors();

            $_SESSION['old_values'] = $_POST;
            $_SESSION['errors'] = $errors->firstOfAll();
            return header('Location: /tasks/add');
        }

        Task::query()
            ->create([
               'name'  => trim($_POST['name']),
               'email' => trim($_POST['email']),
               'text'  => trim($_POST['text']),
            ]);

        $_SESSION['success'] = ['message' => 'Successfully Created'];
        header('Location: /');
    }


    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function editAction()
    {
        $id = $this->route_params["id"];

        $task = Task::query()
            ->select("id", "name", "email", "text", "status")
            ->findOrFail($id);

        View::renderTemplate('Task/edit.html',['task'=> $task]);

    }

    /**
     * Update Task
     */
    public function updateAction()
    {
        $validator = new Validator;

        $validation = $validator->make($_POST, [
            'name'  => 'required|max:100',
            'email' => 'required|email',
            'text'  => 'required|max:500',
        ]);

        $validation->validate();

        if ($validation->fails()) {
            $errors = $validation->errors();

            $_SESSION['old_values'] = $_POST;
            $_SESSION['errors'] = $errors->firstOfAll();

            return header('Location: ' . $_SERVER['HTTP_REFERER']);
        }

        $id = $this->route_params["id"];
        $task = Task::query()
            ->select('id', 'text', 'is_edited')
            ->findOrFail($id);

        $text = trim($_POST['text']);

        $updateData = [
            'name'  => $_POST['name'],
            'email' => $_POST['email'],
            'text'  => $text,
            'status'  => isset($_POST['status']) ? Task::DONE : Task::IN_PROGRESS,
        ];

        if(!$task->is_edited && $task->text !== $text) $updateData['is_edited'] = true;

        $task->update($updateData);

        $_SESSION['success'] = ['message' => 'Successfully Updated'];
        header('Location: /');
    }
}