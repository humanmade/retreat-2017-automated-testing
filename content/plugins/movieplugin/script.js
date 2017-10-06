if (document.querySelector('.movie-search-form') !== null) {
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
}

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

	var messages = document.querySelectorAll('div.notice');
	for (var i = 0, message; message = messages[i]; i++) {
		message.parentNode.removeChild(message);
	}

	if (search_result === null) {
		message = document.createElement('div');
		message.classList.add('notice', 'published', 'error');
		message.innerHTML = 'Could not find movie.';
		document.querySelector('.movie-search-form').appendChild(message);

		return;
	}

	console.log(search_result);
	movie_render_form(search_result);
}

function movie_render_form(movie) {
	var form     = document.querySelector('.movie-entry-form'),
		genre_list = document.getElementsByName('moviegenre[]');

	form.classList.remove('initially-hidden');
	document.querySelector('.movie-search-form').classList.add('initially-hidden');

	// 4) Render data to form.
	form.elements['moviename'].value        = movie.title;
	form.elements['moviedescription'].value = movie.overview;
	form.elements['movierating'].value      = movie.rating[0].toLowerCase();

	for (var i = 0, item; item = genre_list[i]; i++) {
		item.checked = false;

		for (var j = 0, genre; genre_id = movie.genre_ids[j]; j++) {
			if (parseInt(item.getAttribute("data-jsonid"), 10) === parseInt(genre_id, 10)) {
				item.checked = true;
				break;
			}
		}
	}
}
