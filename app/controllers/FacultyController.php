<?php
/**
 * FacultyController.php
 */

require_once ROOT_PATH . '/app/core/Controller.php';
require_once ROOT_PATH . '/app/models/Faculty.php';
require_once ROOT_PATH . '/app/models/User.php';

class FacultyController extends Controller
{
    private Faculty $facultyModel;
    private User $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->facultyModel = new Faculty();
        $this->userModel = new User();
    }

    /**
     * GET /faculties
     * List all faculties
     */
    public function index(): void
    {
        $tenantId = Auth::tenantId();
        $faculties = $this->facultyModel->allWithStats($tenantId);

        $this->view('faculties.index', [
            'faculties' => $faculties
        ]);
    }

    /**
     * GET /faculties/create
     * Show create form
     */
    public function create(): void
    {
        // Only VC/Superadmin can create faculties
        Auth::authorize(['superadmin', 'vc']);

        $tenantId = Auth::tenantId();
        // Get potential deans (professors/senior lecturers)
        $potentialDeans = $this->userModel->getByRole('lecturer', $tenantId);

        $this->view('faculties.create', [
            'potentialDeans' => $potentialDeans
        ]);
    }

    /**
     * POST /faculties/create
     * Store new faculty
     */
    public function store(): void
    {
        Auth::authorize(['superadmin', 'vc']);
        if (!$this->isPost()) $this->redirect('/faculties');

        $name = $this->post('name');
        $code = $this->post('code');
        $deanId = $this->post('dean_id') ? (int)$this->post('dean_id') : null;

        if ($name && $code) {
            $this->facultyModel->create([
                'tenant_id' => Auth::tenantId(),
                'name' => $name,
                'code' => strtoupper($code),
                'dean_id' => $deanId,
                'description' => $this->post('description')
            ]);
            $this->flash('success', "Faculty '{$name}' created successfully.");
        } else {
            $this->flash('error', 'Faculty Name and Code are required.');
        }

        $this->redirect('/faculties');
    }

    /**
     * GET /faculties/{id}
     * Show single faculty with its departments
     */
    public function show(int $id): void
    {
        $tenantId = Auth::tenantId();
        $faculty = $this->facultyModel->findWithDepartments($id, $tenantId);

        if (!$faculty) {
            $this->flash('error', 'Faculty not found.');
            $this->redirect('/faculties');
        }

        $this->view('faculties.show', [
            'faculty' => $faculty
        ]);
    }

    /**
     * GET /faculties/{id}/edit
     * Show edit form
     */
    public function edit(int $id): void
    {
        Auth::authorize(['superadmin', 'vc']);
        $tenantId = Auth::tenantId();
        $faculty = $this->facultyModel->find($id, $tenantId);

        if (!$faculty) {
            $this->redirect('/faculties');
        }

        $potentialDeans = $this->userModel->getByRole('lecturer', $tenantId);

        $this->view('faculties.edit', [
            'faculty' => $faculty,
            'potentialDeans' => $potentialDeans
        ]);
    }

    /**
     * POST /faculties/{id}/edit
     * Update faculty
     */
    public function update(int $id): void
    {
        Auth::authorize(['superadmin', 'vc']);
        if (!$this->isPost()) $this->redirect("/faculties/{$id}/edit");

        $name = $this->post('name');
        $code = $this->post('code');
        $deanId = $this->post('dean_id') ? (int)$this->post('dean_id') : null;

        if ($name && $code) {
            $this->facultyModel->update($id, [
                'name' => $name,
                'code' => strtoupper($code),
                'dean_id' => $deanId,
                'description' => $this->post('description')
            ], Auth::tenantId());
            $this->flash('success', "Faculty updated successfully.");
            $this->redirect("/faculties/{$id}");
        }

        $this->flash('error', 'Name and Code are required.');
        $this->redirect("/faculties/{$id}/edit");
    }

    /**
     * POST /faculties/{id}/delete
     */
    public function destroy(int $id): void
    {
        Auth::authorize(['superadmin', 'vc']);
        
        $this->facultyModel->delete($id, Auth::tenantId());
        $this->flash('success', 'Faculty deleted successfully.');
        $this->redirect('/faculties');
    }
}
