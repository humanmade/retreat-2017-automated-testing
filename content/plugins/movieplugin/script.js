// DOMContentLoaded
document.querySelectorAll('.movie-entry-form').forEach(
	function(currentValue, currentIndex, listObj) {
		currentValue.addEventListener('submit', function(event) {
			event.preventDefault();

			// 1) go request films.json.
			var request = new XMLHttpRequest();
			request.open('GET', '/content/plugins/movieplugin/films.json', true);
			request.addEventListener('load', movie_search_completed);
			request.send();
		});
	}
);

function movie_search_completed(event) {
	if (this.status < 200 || this.status >= 400) {
		// Server returned an error.
		return;
	}

	// Success!
	var films = JSON.parse(this.responseText);

	films.results.forEach(function(film) {
		console.log(film);

		// TODO
		// ----
		//
		// 2) For each item in that 'results', do a basic comparision against 'title' and 'overview'.
		// 3) Return first matching film result.
		// 4) Add form elements (empty textboxes etc) to render the received data into.
		// 5) Create and hook up "save form" logic (probably no-JS POST, for simplicity).
	});
}
