@extends('layouts.penyewa')

@section('content')
    <!-- Page Header -->
    <div class="py-8 px-4 bg-gradient-to-r from-blue-600 to-blue-700 dark:from-blue-800 dark:to-blue-900 text-white">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-3xl font-bold">⭐ Review Saya</h1>
            <p class="text-blue-100 mt-2">Beri penilaian dan review properti yang telah anda sewa</p>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Review Form -->
            @if($properties->isNotEmpty())
                @include('penyewa.reviews.components.review-form', ['properties' => $properties])
            @else
                <div class="bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-700 rounded-lg p-6 mb-8">
                    <p class="text-blue-800 dark:text-blue-200">
                        💡 Anda belum memiliki pemesanan yang selesai. Selesaikan pemesanan terlebih dahulu untuk membuat review.
                    </p>
                </div>
            @endif

            <!-- Reviews List -->
            @if($reviews->isNotEmpty())
                <div class="space-y-6 mt-8">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">📝 Review Saya</h3>

                    @foreach($reviews as $review)
                        @include('penyewa.reviews.components.review-card', [
                            'id' => $review->id_review,
                            'nama_properti' => $review->properti->nama_properti,
                            'tanggal' => $review->created_at->format('d F Y'),
                            'rating' => $review->rating,
                            'review_text' => $review->isi_review,
                            'is_approved' => $review->is_approved
                        ])
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($reviews->hasPages())
                    <div class="mt-8">
                        {{ $reviews->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-12">
                    <p class="text-gray-600 dark:text-gray-400">Anda belum memiliki review</p>
                </div>
            @endif
        </div>
    </div>
@endsection
