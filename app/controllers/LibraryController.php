<?php
/**
 * LibraryController.php
 * Handles books and loans management.
 */

require_once ROOT_PATH . '/app/core/Controller.php';
require_once ROOT_PATH . '/app/models/Book.php';
require_once ROOT_PATH . '/app/models/BookLoan.php';

class LibraryController extends Controller
{
    private Book $bookModel;
    private BookLoan $loanModel;

    public function __construct()
    {
        parent::__construct();
        $this->bookModel = new Book();
        $this->loanModel = new BookLoan();
    }

    /**
     * GET /library
     * Dashboard Overview
     */
    public function index(): void
    {
        $tenantId = Auth::tenantId();
        
        $stats = $this->bookModel->getLibraryStats($tenantId);
        $stats['active_loans'] = $this->loanModel->countActive($tenantId);
        $stats['overdue_loans'] = $this->loanModel->countOverdue($tenantId);
        $stats['recent_loans'] = $this->loanModel->getLoans($tenantId, 'all', 10);

        $this->view('library.index', [
            'stats' => $stats
        ]);
    }

    /**
     * GET /library/books
     * List all books and allow adding new ones.
     */
    public function books(): void
    {
        $tenantId = Auth::tenantId();
        $books = $this->bookModel->all($tenantId);

        $this->view('library.books', [
            'books' => $books
        ]);
    }

    /**
     * POST /library/books
     * Add a new book
     */
    public function storeBook(): void
    {
        if (!$this->isPost()) $this->redirect('/library/books');

        $tenantId = Auth::tenantId();
        $title = $this->post('title');
        $author = $this->post('author');
        $isbn = $this->post('isbn');
        $copies = (int) $this->post('copies_total');

        if ($title && $author) {
            $this->bookModel->create([
                'tenant_id'    => $tenantId,
                'title'        => $title,
                'author'       => $author,
                'isbn'         => $isbn,
                'category'     => $this->post('category'),
                'copies_total' => $copies > 0 ? $copies : 1,
                'copies_avail' => $copies > 0 ? $copies : 1,
            ]);
            $this->flash('success', "Book '{$title}' added successfully.");
        } else {
            $this->flash('error', 'Title and Author are required.');
        }

        $this->redirect('/library/books');
    }
}
