<?php
/**
 * MeetingController.php
 * Manages the advanced calendar and meeting bookings.
 */

require_once ROOT_PATH . '/app/core/Controller.php';
require_once ROOT_PATH . '/app/models/Meeting.php';
require_once ROOT_PATH . '/app/models/User.php';
require_once ROOT_PATH . '/app/models/Notification.php';

class MeetingController extends Controller
{
    private Meeting $meetingModel;
    private User $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->meetingModel = new Meeting();
        $this->userModel    = new User();
    }

    /**
     * GET /calendar
     * Show the main calendar view.
     */
    public function index(): void
    {
        $userId   = Auth::id();
        $tenantId = Auth::tenantId();

        $meetings = $this->meetingModel->getForUser($userId, $tenantId);
        
        // Fetch users for the booking dropdown (colleagues/staff)
        $users = $this->userModel->raw("
            SELECT id, name, role FROM users 
            WHERE tenant_id = :tid AND id != :uid 
            ORDER BY name ASC
        ", [':tid' => $tenantId, ':uid' => $userId]);

        $this->view('meetings.calendar', [
            'meetings' => $meetings,
            'users'    => $users
        ]);
    }

    /**
     * POST /calendar/book
     * Handle new meeting booking.
     */
    public function book(): void
    {
        if (!$this->isPost()) $this->redirect('/calendar');

        $userId   = Auth::id();
        $tenantId = Auth::tenantId();

        $title      = $this->post('title');
        $guestId    = $this->post('guest_id');
        $start      = $this->post('start_time');
        $end        = $this->post('end_time');
        $location   = $this->post('location');
        $desc       = $this->post('description');

        if (!$title || !$start || !$end) {
            $this->flash('error', 'Please fill in all required fields.');
            $this->redirect('/calendar');
        }

        $meetingId = $this->meetingModel->create([
            'tenant_id'   => $tenantId,
            'host_id'     => $userId,
            'guest_id'    => $guestId ?: null,
            'title'       => $title,
            'description' => $desc,
            'start_time'  => $start,
            'end_time'    => $end,
            'location'    => $location,
            'status'      => 'confirmed' // Auto-confirmed for now
        ]);

        if ($meetingId && $guestId) {
            // Notify the guest
            (new Notification())->send(
                (int)$guestId, 
                $tenantId, 
                'New Meeting Invitation', 
                "You have been invited to a meeting: $title", 
                'info', 
                '/calendar'
            );
        }

        $this->flash('success', 'Meeting booked successfully!');
        $this->redirect('/calendar');
    }

    /**
     * POST /calendar/cancel
     */
    public function cancel(): void
    {
        if (!$this->isPost()) $this->redirect('/calendar');

        $id       = $this->post('id');
        $userId   = Auth::id();
        $tenantId = Auth::tenantId();

        $this->meetingModel->query("
            UPDATE meetings SET status = 'cancelled' 
            WHERE id = :id AND tenant_id = :tid AND (host_id = :uid OR guest_id = :uid)
        ", [':id' => $id, ':tid' => $tenantId, ':uid' => $userId]);

        $this->flash('success', 'Meeting cancelled.');
        $this->redirect('/calendar');
    }
}
