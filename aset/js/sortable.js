const dataName = [
	'Tanggal', 'Waktu', 'Lokasi', 'Suhu Tubuh'
];
var thead = document.querySelectorAll('thead th');

function sortTable(head) {
	const index = dataName.indexOf(head);
	let
		direct = thead[index].getAttribute('data-direct'),
		sort = false;

	direct = direct === null ? 'asc' : direct;
	thead.forEach((th) => {
		th.removeAttribute('data-direct');
	});
	thead[index].setAttribute('data-direct', direct == 'asc' ? 'desc' : 'asc');
	while (!sort) {
		let rows = document.querySelectorAll('tbody tr');
		sort = true;

		for (let x = 0; x < rows.length - 1; x++) {
			let
				col = rows[x].children[index],
				colNext = rows[x + 1].children[index];

			if (head == 'Tanggal') {
				col = col.getAttribute('data-date'),
					colNext = colNext.getAttribute('data-date');
			} else {
				col = col.innerText.toLowerCase(),
					colNext = colNext.innerText.toLowerCase();
			}
			let isSwitch =
				direct == 'asc' ?
					col > colNext :
					col < colNext;

			if (isSwitch) {
				rows[x].parentNode.insertBefore(rows[x + 1], rows[x]);
				sort = false;
				break;
			}
		}
	}
}

document.querySelector('#catatan button').onclick = () => {
	const head = document.querySelector('#sort-as').value;
	if (head) {
		sortTable(head);
	}
}

thead.forEach((th) => {
	th.onclick = () => {
		sortTable(th.innerText);
	}
});

function home() {
	document.getElementById('ngisi').style.display = "none";
	document.getElementById('home').style.display = "inline-block";
	document.getElementById('catatan').style.display = "none";
}

function ngisi() {
	document.getElementById('ngisi').style.display = "inline-block";
	document.getElementById('home').style.display = "none";
	document.getElementById('catatan').style.display = "none";
}

function catatan() {
	document.getElementById('ngisi').style.display = "none";
	document.getElementById('home').style.display = "none";
	document.getElementById('catatan').style.display = "inline-block";
}