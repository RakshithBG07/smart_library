document.addEventListener("DOMContentLoaded", async () => {
    await checkAuth();
    
    // Initial fetch to populate data
    fetchBooks();
    fetchFines();
    fetchNotifications();
    fetchGenericData('users', 'usersTableBody', ['id', 'full_name', 'email', 'role_name', 'status']);
    fetchGenericData('authors', 'authorsTableBody', ['id', 'name', 'bio']);
    fetchGenericData('publishers', 'publishersTableBody', ['id', 'name', 'contact']);
    fetchGenericData('categories', 'categoriesTableBody', ['id', 'name', 'description']);
    fetchGenericData('locations', 'locationsTableBody', ['id', 'shelf_no', 'floor']);
    fetchGenericData('borrowings', 'borrowingsTableBody', ['id', 'user_name', 'book_title', 'issue_date', 'status']);
    fetchGenericData('reservations', 'reservationsTableBody', ['id', 'user_name', 'book_title', 'reservation_date', 'status']);

    const hash = window.location.hash.substring(1);
    if (hash) {
        showView(null, hash, true);
    } else {
        history.replaceState({ viewId: 'overview' }, '', '#overview');
    }
});

// Generic Delete Item
async function deleteItem(type, id) {
    if (!confirm(`Are you sure you want to delete this ${type.slice(0, -1)}?`)) return;

    try {
        const res = await fetch(`../api/${type}.php`, {
            method: 'DELETE',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: id })
        });
        const data = await res.json();
        showToast(data.message, data.status);
        if (data.status === 'success') {
            if (type === 'books') fetchBooks();
            else if (type === 'users') fetchGenericData('users', 'usersTableBody', ['id', 'full_name', 'email', 'role_name', 'status']);
        }
    } catch (e) {
        showToast("Deletion failed", "error");
    }
}

async function checkAuth() {
    try {
        const res = await fetch('../api/auth.php');
        const data = await res.json();
        if (data.status === 'success') {
            document.getElementById('userName').textContent = data.user.name;
            document.getElementById('userRole').textContent = data.user.role;
        } else {
            window.location.href = 'login.html';
        }
    } catch (e) {
        window.location.href = 'login.html';
    }
}

// Return Book
async function returnBook(borrowingId) {
    try {
        const res = await fetch('../api/borrow.php', {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ borrowing_id: borrowingId })
        });
        const data = await res.json();
        
        showToast(data.message, data.status);
        if (data.status === 'success') {
            fetchBooks();
            fetchFines();
            fetchGenericData('borrowings', 'borrowingsTableBody', ['id', 'user_name', 'book_title', 'issue_date', 'status']);
        }
    } catch (e) {
        showToast("Error processing request", "error");
    }
}

// Handle browser back/forward buttons
window.addEventListener('popstate', (e) => {
    if (e.state && e.state.viewId) {
        showView(null, e.state.viewId, true);
    } else {
        const hash = window.location.hash.substring(1);
        showView(null, hash || 'overview', true);
    }
});

// View switching logic
function showView(e, viewId, skipHistory = false) {
    if (e) e.preventDefault();
    
    if (!skipHistory) {
        history.pushState({ viewId: viewId }, '', `#${viewId}`);
    }

    document.querySelectorAll('.view-section').forEach(el => el.classList.add('hidden'));
    document.getElementById(`view-${viewId}`).classList.remove('hidden');

    document.querySelectorAll('.nav-item').forEach(el => {
        el.classList.remove('active');
        el.classList.add('text-slate-500');
        el.classList.remove('text-slate-600');
    });
    
    const activeNav = document.getElementById(`nav-${viewId}`);
    activeNav.classList.add('active');
    
    const titles = {
        'overview': 'Overview',
        'books': 'Library Catalog',
        'fines': 'My Fines',
        'users': 'Users Management',
        'authors': 'Authors',
        'publishers': 'Publishers',
        'categories': 'Categories',
        'locations': 'Locations',
        'borrowings': 'All Borrowings',
        'reservations': 'Reservations'
    };
    document.getElementById('viewTitle').textContent = titles[viewId];
}

// Fetch Books from API
async function fetchBooks(search = '') {
    try {
        const url = search ? `../api/books.php?search=${encodeURIComponent(search)}` : '../api/books.php';
        const res = await fetch(url);
        const text = await res.text();
        let data;
        try {
            data = JSON.parse(text);
        } catch (e) {
            console.error("Not JSON:", text);
            return;
        }

        if (data.status === 'success') {
            const tbody = document.getElementById('booksTableBody');
            tbody.innerHTML = '';
            
            document.getElementById('statTotalBooks').textContent = data.data.length;

            data.data.forEach(book => {
                const isAvailable = book.available_copies > 0;
                const statusBadge = isAvailable 
                    ? `<span class="px-2.5 py-1 bg-emerald-50 text-emerald-600 border border-emerald-200 rounded-full text-xs font-semibold tracking-wide">Available (${book.available_copies})</span>`
                    : `<span class="px-2.5 py-1 bg-red-50 text-red-600 border border-red-200 rounded-full text-xs font-semibold tracking-wide">Out of Stock</span>`;
                
                let actionBtns = '';
                if (isAvailable) {
                    actionBtns += `<button onclick="borrowBook(${book.id})" class="text-indigo-600 bg-indigo-50 hover:bg-indigo-600 hover:text-white px-3 py-1.5 rounded-lg font-medium text-xs transition-colors duration-200 mr-2">Borrow</button>`;
                } else {
                    actionBtns += `<button class="text-slate-400 bg-slate-50 px-3 py-1.5 rounded-lg font-medium text-xs cursor-not-allowed mr-2">Reserved</button>`;
                }

                // Admin Delete Button
                actionBtns += `<button onclick="deleteItem('books', ${book.id})" class="text-red-600 bg-red-50 hover:bg-red-600 hover:text-white px-3 py-1.5 rounded-lg font-medium text-xs transition-colors duration-200"><i class="fa-solid fa-trash"></i></button>`;

                const tr = document.createElement('tr');
                tr.className = "hover:bg-slate-50/50 transition-colors";
                tr.innerHTML = `
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-12 bg-slate-100 rounded flex items-center justify-center text-slate-400 shadow-sm">
                                <i class="fa-solid fa-book"></i>
                            </div>
                            <span class="font-semibold text-slate-800">${book.title}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">${book.author_name || 'Unknown'}</td>
                    <td class="px-6 py-4">
                        <span class="bg-slate-100 text-slate-600 px-2.5 py-1 rounded text-xs font-medium">${book.category_name || 'Uncategorized'}</span>
                    </td>
                    <td class="px-6 py-4">${statusBadge}</td>
                    <td class="px-6 py-4 text-right">${actionBtns}</td>
                `;
                tbody.appendChild(tr);
            });
        }
    } catch (e) {
        console.error("Failed to fetch books", e);
    }
}

// Fetch Fines from API
async function fetchFines() {
    try {
        const res = await fetch('../api/fine.php');
        const text = await res.text();
        let data;
        try {
            data = JSON.parse(text);
        } catch(e) {
            return;
        }

        if (data.status === 'success') {
            const tbody = document.getElementById('finesTableBody');
            tbody.innerHTML = '';
            
            let totalUnpaid = 0;

            data.data.forEach(fine => {
                if (fine.status === 'Unpaid') {
                    totalUnpaid += parseFloat(fine.amount);
                }

                const statusBadge = fine.status === 'Paid' 
                    ? `<span class="px-2.5 py-1 bg-emerald-50 text-emerald-600 border border-emerald-200 rounded-full text-xs font-semibold tracking-wide">Paid</span>`
                    : `<span class="px-2.5 py-1 bg-red-50 text-red-600 border border-red-200 rounded-full text-xs font-semibold tracking-wide">Unpaid</span>`;
                
                const actionBtn = fine.status === 'Unpaid'
                    ? `<button onclick="payFine(${fine.id})" class="text-indigo-600 bg-indigo-50 hover:bg-indigo-600 hover:text-white px-4 py-1.5 rounded-lg font-medium text-sm transition-colors duration-200">Pay Now</button>`
                    : `<span class="text-slate-400 font-medium text-sm px-4 py-1.5">Resolved</span>`;

                const tr = document.createElement('tr');
                tr.className = "hover:bg-slate-50/50 transition-colors";
                tr.innerHTML = `
                    <td class="px-6 py-4 font-semibold text-slate-800">#${fine.id}</td>
                    <td class="px-6 py-4 text-slate-600 font-medium">${fine.user_name || 'Me'}</td>
                    <td class="px-6 py-4 font-bold text-slate-700">$${parseFloat(fine.amount).toFixed(2)}</td>
                    <td class="px-6 py-4">${new Date(fine.created_at).toLocaleDateString()}</td>
                    <td class="px-6 py-4">${statusBadge}</td>
                    <td class="px-6 py-4 text-right">${actionBtn}</td>
                `;
                tbody.appendChild(tr);
            });

            document.getElementById('statFines').textContent = `$${totalUnpaid.toFixed(2)}`;
        }
    } catch (e) {
        console.error("Failed to fetch fines", e);
    }
}

async function fetchGenericData(endpoint, tbodyId, fields) {
    try {
        const res = await fetch(`../api/${endpoint}.php`);
        const text = await res.text();
        const data = JSON.parse(text);
        if (data.status === 'success') {
            const tbody = document.getElementById(tbodyId);
            tbody.innerHTML = '';
            
            if (endpoint === 'borrowings') {
                const activeCount = data.data.filter(b => b.status === 'Active').length;
                const statEl = document.getElementById('statBorrowings');
                if(statEl) statEl.textContent = activeCount;
            }

            data.data.forEach(item => {
                const tr = document.createElement('tr');
                tr.className = "hover:bg-slate-50/50 transition-colors";
                let html = '';
                fields.forEach(f => {
                    if (f === 'status') {
                        const colors = {
                            'Active': 'bg-emerald-50 text-emerald-600 border-emerald-200',
                            'Returned': 'bg-slate-50 text-slate-600 border-slate-200',
                            'Overdue': 'bg-red-50 text-red-600 border-red-200',
                            'Paid': 'bg-emerald-50 text-emerald-600 border-emerald-200',
                            'Unpaid': 'bg-red-50 text-red-600 border-red-200',
                            'pending': 'bg-amber-50 text-amber-600 border-amber-200'
                        };
                        const cls = colors[item[f]] || 'bg-slate-50 text-slate-600 border-slate-200';
                        html += `<td class="px-6 py-4"><span class="px-2.5 py-1 ${cls} border rounded-full text-xs font-semibold tracking-wide">${item[f]}</span></td>`;
                    } else {
                        html += `<td class="px-6 py-4">${item[f] !== null ? item[f] : ''}</td>`;
                    }
                });

                // Add Actions column for Borrowings & Users
                if (endpoint === 'borrowings') {
                    if (item.status === 'Active' || item.status === 'Overdue') {
                        html += `<td class="px-6 py-4 text-right"><button onclick="returnBook(${item.id})" class="text-indigo-600 bg-indigo-50 hover:bg-indigo-600 hover:text-white px-4 py-1.5 rounded-lg font-medium text-sm transition-colors duration-200">Return</button></td>`;
                    } else {
                        html += `<td class="px-6 py-4 text-right text-slate-400 text-sm">--</td>`;
                    }
                } else if (endpoint === 'users') {
                    html += `<td class="px-6 py-4 text-right"><button onclick="deleteItem('users', ${item.id})" class="text-red-600 bg-red-50 hover:bg-red-600 hover:text-white px-3 py-1.5 rounded-lg font-medium text-xs transition-colors duration-200"><i class="fa-solid fa-trash"></i></button></td>`;
                }

                tr.innerHTML = html;
                tbody.appendChild(tr);
            });
        }
    } catch(e) {}
}

async function fetchNotifications() {
    try {
        const res = await fetch('../api/notifications.php');
        const text = await res.text();
        const data = JSON.parse(text);
        if (data.status === 'success' && data.data.length > 0) {
            document.getElementById('notifBadge').classList.remove('hidden');
            const list = document.getElementById('notifList');
            list.innerHTML = '';
            data.data.forEach(n => {
                const color = n.type === 'error' ? 'text-red-600 bg-red-50' : 'text-amber-600 bg-amber-50';
                list.innerHTML += `<div class="p-3 mb-2 rounded-lg text-sm font-medium ${color}">${n.message}</div>`;
            });
        }
    } catch(e){}
}

function toggleNotifications() {
    document.getElementById('notifDropdown').classList.toggle('hidden');
}

// Borrow Book
async function borrowBook(bookId) {
    try {
        const res = await fetch('../api/borrow.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ book_id: bookId })
        });
        const data = await res.json();
        
        showToast(data.message, data.status);
        if (data.status === 'success') {
            fetchBooks();
            fetchGenericData('borrowings', 'borrowingsTableBody', ['id', 'user_name', 'book_title', 'issue_date', 'status']);
            // Also refresh fines if a new borrowing somehow triggered one (though usually it doesn't)
            fetchFines();
        }
    } catch (e) {
        showToast("Error processing request", "error");
    }
}

// Pay Fine
async function payFine(fineId) {
    try {
        const res = await fetch('../api/fine.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ fine_id: fineId })
        });
        const data = await res.json();
        
        showToast(data.message, data.status);
        if (data.status === 'success') {
            fetchFines();
            fetchNotifications();
        }
    } catch (e) {
        showToast("Error processing request", "error");
    }
}

// Google-like Search Logic
const searchInput = document.getElementById('globalSearch');
const suggestionsBox = document.getElementById('searchSuggestions');

searchInput.addEventListener('input', (e) => {
    const query = e.target.value.trim();
    if (query.length < 1) {
        suggestionsBox.classList.add('hidden');
        return;
    }

    clearTimeout(window.searchTimeout);
    window.searchTimeout = setTimeout(async () => {
        try {
            const res = await fetch(`../api/books.php?search=${encodeURIComponent(query)}&limit=8`);
            const data = await res.json();
            
            if (data.status === 'success' && data.data.length > 0) {
                renderSuggestions(data.data);
            } else {
                suggestionsBox.classList.add('hidden');
            }
        } catch (e) {
            console.error("Search failed", e);
        }
    }, 200);
});

function renderSuggestions(items) {
    suggestionsBox.innerHTML = '';
    items.forEach(item => {
        const div = document.createElement('div');
        div.className = 'suggestion-item';
        div.innerHTML = `
            <i class="fa-solid fa-book"></i>
            <div>
                <span class="title">${item.title}</span>
                <span class="subtitle">${item.author_name} • ${item.category_name}</span>
            </div>
        `;
        div.onclick = () => {
            searchInput.value = item.title;
            suggestionsBox.classList.add('hidden');
            showView(null, 'books');
            fetchBooks(item.title);
        };
        suggestionsBox.appendChild(div);
    });
    suggestionsBox.classList.remove('hidden');
}

searchInput.addEventListener('keydown', (e) => {
    if (e.key === 'Enter') {
        const query = searchInput.value.trim();
        suggestionsBox.classList.add('hidden');
        showView(null, 'books');
        fetchBooks(query);
    }
});

// Close suggestions on click outside
document.addEventListener('click', (e) => {
    if (!searchInput.contains(e.target) && !suggestionsBox.contains(e.target)) {
        suggestionsBox.classList.add('hidden');
    }
});

// Toast Notification System
function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');
    const msg = document.getElementById('toastMsg');
    const icon = document.getElementById('toastIcon');
    
    msg.textContent = message;
    
    if (type === 'success') {
        icon.className = 'fa-solid fa-circle-check text-emerald-400 text-2xl';
        toast.className = 'fixed bottom-6 right-6 bg-slate-800 text-white px-6 py-4 rounded-xl shadow-2xl transform transition-all duration-300 z-50 flex items-center gap-4 min-w-[300px] border-l-4 border-emerald-400 translate-y-0 opacity-100';
    } else {
        icon.className = 'fa-solid fa-circle-xmark text-red-400 text-2xl';
        toast.className = 'fixed bottom-6 right-6 bg-slate-800 text-white px-6 py-4 rounded-xl shadow-2xl transform transition-all duration-300 z-50 flex items-center gap-4 min-w-[300px] border-l-4 border-red-400 translate-y-0 opacity-100';
    }
    
    setTimeout(() => {
        toast.classList.add('translate-y-24', 'opacity-0');
        toast.classList.remove('translate-y-0', 'opacity-100');
    }, 3000);
}

// Modal Logic
async function populateAddBookSelects() {
    const selects = {
        'bookAuthorId': 'authors',
        'bookCategoryId': 'categories',
        'bookPublisherId': 'publishers',
        'bookLocationId': 'locations'
    };

    for (const [id, endpoint] of Object.entries(selects)) {
        try {
            const res = await fetch(`../api/${endpoint}.php`);
            const data = await res.json();
            const select = document.getElementById(id);
            select.innerHTML = '<option value="">Select Option</option>';
            if (data.status === 'success') {
                data.data.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.id;
                    option.textContent = item.name || item.shelf_no || item.id;
                    select.appendChild(option);
                });
            }
        } catch (e) { console.error(`Failed to load ${endpoint}`, e); }
    }
}

document.getElementById('addBookBtn').addEventListener('click', () => {
    const modal = document.getElementById('addBookModal');
    const content = document.getElementById('addBookModalContent');
    
    // Populate dropdowns before showing
    populateAddBookSelects();
    
    modal.classList.remove('hidden');
    setTimeout(() => {
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }, 10);
});

// User Modal Logic
document.getElementById('addUserBtn').addEventListener('click', () => {
    const modal = document.getElementById('addUserModal');
    const content = document.getElementById('addUserModalContent');
    modal.classList.remove('hidden');
    setTimeout(() => {
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }, 10);
});

function closeAddUserModal() {
    const modal = document.getElementById('addUserModal');
    const content = document.getElementById('addUserModalContent');
    content.classList.add('scale-95', 'opacity-0');
    content.classList.remove('scale-100', 'opacity-100');
    setTimeout(() => modal.classList.add('hidden'), 200);
}

document.getElementById('addUserForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const payload = {
        full_name: document.getElementById('userFullName').value,
        email: document.getElementById('userEmail').value,
        role_id: document.getElementById('userRoleId').value,
        status: document.getElementById('userStatus').value,
        password: document.getElementById('userPassword').value
    };

    try {
        const res = await fetch('../api/users.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });
        const data = await res.json();
        showToast(data.message, data.status);
        if (data.status === 'success') {
            closeAddUserModal();
            document.getElementById('addUserForm').reset();
            fetchGenericData('users', 'usersTableBody', ['id', 'full_name', 'email', 'role_name', 'status']);
        }
    } catch (e) {
        showToast('Error adding user', 'error');
    }
});

function closeAddBookModal() {
    const modal = document.getElementById('addBookModal');
    const content = document.getElementById('addBookModalContent');
    content.classList.remove('scale-100', 'opacity-100');
    content.classList.add('scale-95', 'opacity-0');
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 200);
}

document.getElementById('addBookForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const payload = {
        title: document.getElementById('bookTitle').value,
        author_id: document.getElementById('bookAuthorId').value,
        category_id: document.getElementById('bookCategoryId').value,
        publisher_id: document.getElementById('bookPublisherId').value,
        location_id: document.getElementById('bookLocationId').value,
        isbn: document.getElementById('bookIsbn').value,
        total_copies: document.getElementById('bookCopies').value
    };

    try {
        const res = await fetch('../api/books.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });
        const data = await res.json();
        showToast(data.message, data.status);
        if (data.status === 'success') {
            closeAddBookModal();
            document.getElementById('addBookForm').reset();
            fetchBooks();
        }
    } catch (e) {
        showToast('Error adding book', 'error');
    }
});

function logout() {
    window.location.href = 'login.html';
}
