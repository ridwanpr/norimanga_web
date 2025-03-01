document.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll('.bookmark-btn').forEach(button => {
    button.addEventListener('click', function () {
      let mangaId = this.getAttribute('data-id');
      let button = this;

      axios.post('/bookmark/toggle', {
        manga_id: mangaId
      })
        .then(response => {
          if (response.data.bookmarked) {
            button.classList.add('bg-danger');
            button.classList.remove('btn-success');
            button.querySelector('span').textContent = 'Bookmarked';
          } else {
            button.classList.add('btn-success');
            button.classList.remove('bg-danger');
            button.querySelector('span').textContent = 'Bookmark';
          }
        })
        .catch(error => {
          console.error("Error:", error);
        });
    });
  });
});