document.querySelector('.movie-search-form')
	.addEventListener('submit', function(event) {
		event.preventDefault();

		// 1) go request films.json.
		var request = new XMLHttpRequest();
		request.open('GET', '/content/plugins/movieplugin/films.json', true);
		request.addEventListener('load', movie_search_completed);
		request.send();
	}
);

function movie_search_completed(event) {
	if (this.status < 200 || this.status >= 400) {
		return;
	}

	// Success!
	var films = JSON.parse(this.responseText),
		search_result = null;

	films.results.some(function(film, _) {
		var query    = document.querySelector('.moviesearch').value.toLowerCase(),
			film_title = film.title.toLowerCase(),
			film_desc  = film.overview.toLowerCase();

		// 2) For each item in that 'results', do a basic search.
		if (film_title.indexOf(query) < 0 && film_desc.indexOf(query) < 0) {
			return false;
		}

		// 3) Return first matching film result.
		search_result = film;

		return true;
	});

	if (search_result === null) {
		return;
	}

	console.log(search_result);
	movie_render_form(search_result);
}

function movie_render_form(movie) {
	document.querySelector('.movie-search-form').classList.add('initially-hidden');
	document.querySelector('.movie-entry-form').classList.remove('initially-hidden');

	alert(movie.title);
	// 4) Add form elements (empty textboxes etc) to render the received data into.
	// 5) Create and hook up "save form" logic (probably no-JS POST, for simplicity).
}