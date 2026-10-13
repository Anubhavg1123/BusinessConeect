// Interactive UI Controllers - Business Connect
document.addEventListener('DOMContentLoaded', () => {

  // Live Search Autosuggest Input Handler
  const searchInput = document.getElementById('global-search-input');
  const searchResultsBox = document.getElementById('search-suggestions-dropdown');

  if (searchInput && searchResultsBox) {
    let timer;
    searchInput.addEventListener('input', (e) => {
      clearTimeout(timer);
      const val = e.target.value.trim();
      if (val.length < 2) {
        searchResultsBox.classList.add('d-none');
        return;
      }

      timer = setTimeout(() => {
        fetch(`/api/search/suggestions?term=${encodeURIComponent(val)}`)
          .then(res => res.json())
          .then(data => {
            if (!data.businesses || data.businesses.length === 0) {
              searchResultsBox.innerHTML = '<div class="p-3 text-muted">No businesses found matching your query</div>';
            } else {
              let html = '<div class="list-group list-group-flush">';
              data.businesses.forEach(b => {
                html += `
                  <a href="/businesses/${b.id}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                    <div>
                      <strong>${b.name}</strong>
                      <div class="small text-muted"><i class="bi bi-geo-alt me-1"></i>${b.city || 'Online'}</div>
                    </div>
                    <span class="badge bg-primary rounded-pill">View</span>
                  </a>
                `;
              });
              html += '</div>';
              searchResultsBox.innerHTML = html;
            }
            searchResultsBox.classList.remove('d-none');
          })
          .catch(() => {
            searchResultsBox.classList.add('d-none');
          });
      }, 300);
    });

    document.addEventListener('click', (e) => {
      if (!searchInput.contains(e.target) && !searchResultsBox.contains(e.target)) {
        searchResultsBox.classList.add('d-none');
      }
    });
  }

  // SweetAlert Delete Confirmation Hook
  document.querySelectorAll('.confirm-delete-btn').forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      const form = btn.closest('form');
      if (typeof Swal !== 'undefined') {
        Swal.fire({
          title: 'Are you sure?',
          text: "This action cannot be undone!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#ef4444',
          cancelButtonColor: '#64748b',
          confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
          if (result.isConfirmed) {
            form.submit();
          }
        });
      } else {
        if (confirm('Are you sure you want to delete this record?')) {
          form.submit();
        }
      }
    });
  });

  // Image Drag and Drop Preview
  const dropZone = document.getElementById('image-dropzone');
  const fileInput = document.getElementById('image-file-input');
  const previewImg = document.getElementById('image-preview-target');

  if (dropZone && fileInput) {
    dropZone.addEventListener('click', () => fileInput.click());
    fileInput.addEventListener('change', (e) => {
      const file = e.target.files[0];
      if (file && previewImg) {
        const reader = new FileReader();
        reader.onload = (evt) => {
          previewImg.src = evt.target.result;
          previewImg.classList.remove('d-none');
        };
        reader.readAsDataURL(file);
      }
    });
  }
});
