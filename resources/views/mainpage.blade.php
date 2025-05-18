@extends('layout')

@section('title', 'Tipa Liquidpedia - Sports')

@section('content')
    <div class="sports-container">
        <div class="sports-header">
            <h1>Sports</h1>
            <button id="add-sport-btn" class="add-sport-btn">Add Sport</button>
        </div>

        <div class="sports-grid">
            @foreach ($sports as $sport)
                <div class="sport-card-container">
                    <a href="{{ route('sport', ['id'=> $sport->id]) }}" class="sport-card">
                        <div class="sport-letter" style="background-color: {{ $loop->index % 2 == 0 ? '#3b82f6' : '#10b981' }};">
                            {{ strtoupper(substr($sport->name, 0, 1)) }}
                        </div>
                        <div class="sport-name">
                            {{ $sport->name }}
                        </div>
                    </a>
                    <div class="sport-actions">
                        <button class="edit-sport-btn" data-id="{{ $sport->id }}" data-name="{{ $sport->name }}">Edit</button>
                        <button class="delete-sport-btn" data-id="{{ $sport->id }}">Delete</button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Add Sport Modal -->
    <div id="add-sport-modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Add New Sport</h2>
            <form id="add-sport-form">
                <div class="form-group">
                    <label for="sport-name">Sport Name</label>
                    <input type="text" id="sport-name" name="name" required>
                </div>
                <button type="submit">Add</button>
            </form>
            <div id="add-sport-error" class="error-message"></div>
        </div>
    </div>

    <!-- Edit Sport Modal -->
    <div id="edit-sport-modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Edit Sport</h2>
            <form id="edit-sport-form">
                <input type="hidden" id="edit-sport-id" name="id">
                <div class="form-group">
                    <label for="edit-sport-name">Sport Name</label>
                    <input type="text" id="edit-sport-name" name="name" required>
                </div>
                <button type="submit">Save</button>
            </form>
            <div id="edit-sport-error" class="error-message"></div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="delete-sport-modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Confirm Deletion</h2>
            <p>Are you sure you want to delete this sport?</p>
            <div class="delete-actions">
                <button id="confirm-delete" class="delete-btn">Delete</button>
                <button id="cancel-delete" class="cancel-btn">Cancel</button>
            </div>
            <div id="delete-sport-error" class="error-message"></div>
        </div>
    </div>

    <style>
        .sports-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .sports-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .sports-container h1 {
            font-size: 2rem;
            font-weight: bold;
            color: #1f2937;
            margin: 0;
        }

        .add-sport-btn {
            background-color: #3b82f6;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
        }

        .add-sport-btn:hover {
            background-color: #2563eb;
        }

        .sports-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 24px;
        }

        .sport-card-container {
            position: relative;
        }

        .sport-card {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s ease;
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .sport-card:hover {
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        }

        .sport-letter {
            height: 150px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 72px;
            font-weight: bold;
            color: white;
        }

        .sport-name {
            padding: 16px;
            font-size: 1.25rem;
            font-weight: 600;
            color: #1f2937;
        }

        .sport-actions {
            display: flex;
            gap: 8px;
            margin-top: 8px;
        }

        .sport-actions button {
            flex: 1;
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
        }

        .edit-sport-btn {
            background-color: #f59e0b;
            color: white;
        }

        .edit-sport-btn:hover {
            background-color: #d97706;
        }

        .delete-sport-btn {
            background-color: #ef4444;
            color: white;
        }

        .delete-sport-btn:hover {
            background-color: #dc2626;
        }

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border-radius: 8px;
            width: 400px;
            max-width: 90%;
            position: relative;
        }

        .close {
            position: absolute;
            right: 20px;
            top: 10px;
            font-size: 28px;
            font-weight: bold;
            color: #aaa;
            cursor: pointer;
        }

        .close:hover {
            color: #555;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .modal-content button[type="submit"] {
            background-color: #3b82f6;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
        }

        .modal-content button[type="submit"]:hover {
            background-color: #2563eb;
        }

        .delete-actions {
            display: flex;
            gap: 8px;
            margin-top: 16px;
        }

        .delete-btn {
            background-color: #ef4444;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            flex: 1;
        }

        .delete-btn:hover {
            background-color: #dc2626;
        }

        .cancel-btn {
            background-color: #e5e7eb;
            color: #1f2937;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            flex: 1;
        }

        .cancel-btn:hover {
            background-color: #d1d5db;
        }

        .error-message {
            color: #ef4444;
            margin-top: 12px;
            font-size: 14px;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Modal elements
            const addSportModal = document.getElementById('add-sport-modal');
            const editSportModal = document.getElementById('edit-sport-modal');
            const deleteSportModal = document.getElementById('delete-sport-modal');

            // Buttons
            const addSportBtn = document.getElementById('add-sport-btn');
            const closeButtons = document.getElementsByClassName('close');

            // Forms
            const addSportForm = document.getElementById('add-sport-form');
            const editSportForm = document.getElementById('edit-sport-form');

            // Error messages
            const addSportError = document.getElementById('add-sport-error');
            const editSportError = document.getElementById('edit-sport-error');
            const deleteSportError = document.getElementById('delete-sport-error');

            // Delete confirmation
            let currentDeleteId = null;

            // Open add sport modal
            addSportBtn.onclick = function() {
                addSportModal.style.display = 'block';
                addSportError.textContent = '';
                addSportForm.reset();
            };

            // Open edit sport modal
            document.querySelectorAll('.edit-sport-btn').forEach(btn => {
                btn.onclick = function(e) {
                    e.preventDefault();
                    const id = this.getAttribute('data-id');
                    const name = this.getAttribute('data-name');

                    document.getElementById('edit-sport-id').value = id;
                    document.getElementById('edit-sport-name').value = name;
                    editSportError.textContent = '';
                    editSportModal.style.display = 'block';
                };
            });

            // Open delete confirmation modal
            document.querySelectorAll('.delete-sport-btn').forEach(btn => {
                btn.onclick = function(e) {
                    e.preventDefault();
                    currentDeleteId = this.getAttribute('data-id');
                    deleteSportError.textContent = '';
                    deleteSportModal.style.display = 'block';
                };
            });

            // Close modals
            Array.from(closeButtons).forEach(btn => {
                btn.onclick = function() {
                    addSportModal.style.display = 'none';
                    editSportModal.style.display = 'none';
                    deleteSportModal.style.display = 'none';
                };
            });

            // Close modals when clicking outside
            window.onclick = function(event) {
                if (event.target == addSportModal) {
                    addSportModal.style.display = 'none';
                }
                if (event.target == editSportModal) {
                    editSportModal.style.display = 'none';
                }
                if (event.target == deleteSportModal) {
                    deleteSportModal.style.display = 'none';
                }
            };

            // Cancel delete
            document.getElementById('cancel-delete').onclick = function() {
                deleteSportModal.style.display = 'none';
            };

            // Add sport form submission
            addSportForm.onsubmit = async function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const name = formData.get('name');

                try {
                    const response = await fetch('{{ route("create_sport") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ name })
                    });



                    if (!response.ok) {
                        const data = await response.json();
                        throw data;
                    }

                    // Reload the page to show the new sport
                    window.location.reload();
                } catch (error) {
                    addSportError.textContent = error.message || 'An error occurred while adding the sport.';
                }
            };

            // Edit sport form submission
            editSportForm.onsubmit = async function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const id = formData.get('id');
                const name = formData.get('name');

                try {
                    const response = await fetch(`/api/v1/sports/${id}`, {
                        method: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ name })
                    });



                    if (!response.ok) {
                        const data = await response.json();
                        throw data;
                    }

                    // Reload the page to show the updated sport
                    window.location.reload();
                } catch (error) {
                    editSportError.textContent = error.message || 'An error occurred while updating the sport.';
                }
            };

            // Confirm delete
            document.getElementById('confirm-delete').onclick = async function() {
                try {
                    const response = await fetch(`/api/v1/sports/${currentDeleteId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    });



                    if (!response.ok) {
                        const data = await response.json();
                        throw data;
                    }

                    // Reload the page to reflect the deletion
                    window.location.reload();
                } catch (error) {
                    deleteSportError.textContent = error.message || 'An error occurred while deleting the sport.';
                }
            };
        });
    </script>
@endsection
