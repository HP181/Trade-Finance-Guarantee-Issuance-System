<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGuaranteeRequest;
use App\Http\Requests\UpdateGuaranteeRequest;
use App\Models\Guarantee;
use App\Repositories\Interfaces\GuaranteeRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuaranteeController extends Controller
{
    protected $guaranteeRepository;

    /**
     * Create a new controller instance.
     */
    public function __construct(GuaranteeRepositoryInterface $guaranteeRepository)
{
    $this->guaranteeRepository = $guaranteeRepository;
}


    /**
     * Display a listing of guarantees.
     */
    public function index()
    {
        try {
            $guarantees = $this->guaranteeRepository->getAllWithPagination();
            return view('guarantees.index', compact('guarantees'));
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Error in GuaranteeController@index: ' . $e->getMessage());
            
            // Return view with empty collection
            return view('guarantees.index', ['guarantees' => new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10)]);
        }
    }

    /**
     * Show the form for creating a new guarantee.
     */
    public function create()
    {
        $guaranteeTypes = Guarantee::GUARANTEE_TYPES;
        
        return view('guarantees.create', compact('guaranteeTypes'));
    }

    /**
     * Store a newly created guarantee in storage.
     */
    public function store(StoreGuaranteeRequest $request)
    {
        $data = $request->validated();
        $data['created_by'] = Auth::id();
        
        $guarantee = $this->guaranteeRepository->create($data);
        
        return redirect()->route('guarantees.show', $guarantee)
            ->with('success', 'Guarantee created successfully.');
    }

    /**
     * Display the specified guarantee.
     */
    public function show(Guarantee $guarantee)
    {
        $guarantee = $this->guaranteeRepository->findById($guarantee->id);
        
        return view('guarantees.show', compact('guarantee'));
    }

    /**
     * Show the form for editing the specified guarantee.
     */
    public function edit(Guarantee $guarantee)
    {
        $guaranteeTypes = Guarantee::GUARANTEE_TYPES;
        
        return view('guarantees.edit', compact('guarantee', 'guaranteeTypes'));
    }

    /**
     * Update the specified guarantee in storage.
     */
    public function update(UpdateGuaranteeRequest $request, Guarantee $guarantee)
    {
        $data = $request->validated();
        $data['updated_by'] = Auth::id();
        
        $this->guaranteeRepository->update($guarantee, $data);
        
        return redirect()->route('guarantees.show', $guarantee)
            ->with('success', 'Guarantee updated successfully.');
    }

    /**
     * Remove the specified guarantee from storage.
     */
    public function destroy(Guarantee $guarantee)
    {
        $this->guaranteeRepository->delete($guarantee);
        
        return redirect()->route('guarantees.index')
            ->with('success', 'Guarantee deleted successfully.');
    }

    /**
     * Submit a guarantee for review.
     */
    public function submitForReview(Guarantee $guarantee)
    {
        $this->guaranteeRepository->submitForReview($guarantee, Auth::id());
        
        return redirect()->route('guarantees.show', $guarantee)
            ->with('success', 'Guarantee submitted for review.');
    }

    /**
     * Apply for a guarantee.
     */
    public function applyForGuarantee(Guarantee $guarantee)
    {
        $this->guaranteeRepository->applyForGuarantee($guarantee, Auth::id());
        
        return redirect()->route('guarantees.show', $guarantee)
            ->with('success', 'Guarantee application submitted.');
    }

    /**
     * Issue a guarantee.
     */
    public function issueGuarantee(Guarantee $guarantee)
    {
        $this->guaranteeRepository->issueGuarantee($guarantee, Auth::id());
        
        return redirect()->route('guarantees.show', $guarantee)
            ->with('success', 'Guarantee issued successfully.');
    }

    /**
     * Reject a guarantee.
     */
    public function rejectGuarantee(Request $request, Guarantee $guarantee)
    {
        $this->guaranteeRepository->rejectGuarantee($guarantee, Auth::id(), $request->reason);
        
        return redirect()->route('guarantees.show', $guarantee)
            ->with('success', 'Guarantee rejected.');
    }

}