<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Book;
use App\Models\Reader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $query = Reservation::with(['book', 'reader', 'user']);

        // Lọc theo trạng thái
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Lọc theo sách
        if ($request->filled('book_id')) {
            $query->where('book_id', $request->book_id);
        }

        // Lọc theo độc giả
        if ($request->filled('reader_id')) {
            $query->where('reader_id', $request->reader_id);
        }

        // Lọc đặt trước hết hạn
        if ($request->filled('expired')) {
            $query->expired();
        }

        $reservations = $query->orderBy('created_at', 'desc')->paginate(20);
        $books = Book::all();
        $readers = Reader::all();

        return view('admin.reservations.index', compact('reservations', 'books', 'readers'));
    }

    public function create(Request $request)
    {
        $bookId = $request->get('book_id');
        $book = null;
        
        if ($bookId) {
            $book = Book::findOrFail($bookId);
        }

        $books = Book::where('can_be_reserved', true)->get();
        $readers = Reader::where('trang_thai', 'Hoat dong')->get();

        return view('admin.reservations.create', compact('book', 'books', 'readers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'reader_id' => 'required|exists:readers,id',
            'priority' => 'nullable|integer|min:1|max:5',
            'notes' => 'nullable|string|max:500',
        ]);

        $book = Book::findOrFail($request->book_id);
        $reader = Reader::findOrFail($request->reader_id);

        // Kiểm tra sách có thể đặt trước không
        if (!$book->canBeReserved()) {
            return back()->withErrors(['book_id' => 'Sách này hiện tại không thể đặt trước.']);
        }

        // Kiểm tra user đã đặt trước sách này chưa
        $existingReservation = Reservation::where('book_id', $request->book_id)
            ->where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'confirmed', 'ready'])
            ->first();

        if ($existingReservation) {
            return back()->withErrors(['book_id' => 'Bạn đã đặt trước sách này rồi.']);
        }

        // Tính ngày hết hạn (7 ngày từ ngày đặt trước)
        $reservationDate = Carbon::today();
        $expiryDate = $reservationDate->copy()->addDays(7);

        Reservation::create([
            'book_id' => $request->book_id,
            'reader_id' => $request->reader_id,
            'user_id' => Auth::id(),
            'priority' => $request->priority ?? 1,
            'reservation_date' => $reservationDate,
            'expiry_date' => $expiryDate,
            'notes' => $request->notes,
        ]);

        return redirect()->route('admin.reservations.index')
            ->with('success', 'Đặt trước sách thành công!');
    }

    public function show($id)
    {
        $reservation = Reservation::with(['book', 'reader', 'user'])->findOrFail($id);
        return view('admin.reservations.show', compact('reservation'));
    }

    public function edit($id)
    {
        $reservation = Reservation::findOrFail($id);
        $books = Book::all();
        $readers = Reader::all();
        
        return view('admin.reservations.edit', compact('reservation', 'books', 'readers'));
    }

    public function update(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,confirmed,ready,cancelled,expired',
            'priority' => 'nullable|integer|min:1|max:5',
            'expiry_date' => 'nullable|date|after:today',
            'ready_date' => 'nullable|date',
            'pickup_date' => 'nullable|date',
            'notes' => 'nullable|string|max:500',
        ]);

        $updateData = [
            'status' => $request->status,
            'priority' => $request->priority ?? $reservation->priority,
            'notes' => $request->notes,
        ];

        if ($request->expiry_date) {
            $updateData['expiry_date'] = $request->expiry_date;
        }

        if ($request->ready_date) {
            $updateData['ready_date'] = $request->ready_date;
        }

        if ($request->pickup_date) {
            $updateData['pickup_date'] = $request->pickup_date;
        }

        $reservation->update($updateData);

        return redirect()->route('admin.reservations.show', $reservation->id)
            ->with('success', 'Đặt trước đã được cập nhật thành công!');
    }

    public function destroy($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->delete();

        return back()->with('success', 'Đặt trước đã được xóa thành công!');
    }

    // Xác nhận đặt trước
    public function confirm($id)
    {
        $reservation = Reservation::findOrFail($id);
        
        $reservation->update([
            'status' => 'confirmed',
        ]);

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Đặt trước đã được xác nhận!']);
        }

        return back()->with('success', 'Đặt trước đã được xác nhận!');
    }

    // Đánh dấu sẵn sàng
    public function markReady($id)
    {
        $reservation = Reservation::findOrFail($id);
        
        $reservation->update([
            'status' => 'ready',
            'ready_date' => Carbon::today(),
        ]);

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Sách đã sẵn sàng để nhận!']);
        }

        return back()->with('success', 'Sách đã sẵn sàng để nhận!');
    }

    // Hủy đặt trước
    public function cancel($id)
    {
        $reservation = Reservation::findOrFail($id);
        
        $reservation->update([
            'status' => 'cancelled',
        ]);

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Đặt trước đã được hủy!']);
        }

        return back()->with('success', 'Đặt trước đã được hủy!');
    }

    // Tự động hủy đặt trước hết hạn
    public function cancelExpiredReservations()
    {
        $expiredReservations = Reservation::expired()->get();
        $cancelledCount = 0;

        foreach ($expiredReservations as $reservation) {
            $reservation->update(['status' => 'expired']);
            $cancelledCount++;
        }

        return response()->json([
            'status' => 'success',
            'message' => "Đã hủy {$cancelledCount} đặt trước hết hạn",
            'cancelled_count' => $cancelledCount
        ]);
    }

    // API endpoint để đặt trước sách
    public function createReservation(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'reader_id' => 'required|exists:readers,id',
            'priority' => 'nullable|integer|min:1|max:5',
            'notes' => 'nullable|string|max:500',
        ]);

        $book = Book::findOrFail($request->book_id);

        if (!$book->canBeReserved()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Sách này hiện tại không thể đặt trước.'
            ], 400);
        }

        $existingReservation = Reservation::where('book_id', $request->book_id)
            ->where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'confirmed', 'ready'])
            ->first();

        if ($existingReservation) {
            return response()->json([
                'status' => 'error',
                'message' => 'Bạn đã đặt trước sách này rồi.'
            ], 400);
        }

        $reservation = Reservation::create([
            'book_id' => $request->book_id,
            'reader_id' => $request->reader_id,
            'user_id' => Auth::id(),
            'priority' => $request->priority ?? 1,
            'reservation_date' => Carbon::today(),
            'expiry_date' => Carbon::today()->addDays(7),
            'notes' => $request->notes,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Đặt trước sách thành công!',
            'data' => $reservation->load(['book', 'reader'])
        ], 201);
    }

    // API endpoint để lấy đặt trước của user
    public function getUserReservations()
    {
        $reservations = Reservation::with(['book', 'reader'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $reservations
        ]);
    }

    // Export reservations to Excel
    public function export()
    {
        $reservations = Reservation::with(['book', 'reader', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = 'reservations_' . date('Y-m-d_H-i-s') . '.xlsx';
        
        // For now, return a simple response. You can implement Excel export later
        return response()->json([
            'success' => true,
            'message' => 'Chức năng xuất Excel sẽ được triển khai sớm.',
            'filename' => $filename,
            'count' => $reservations->count()
        ]);
    }
}