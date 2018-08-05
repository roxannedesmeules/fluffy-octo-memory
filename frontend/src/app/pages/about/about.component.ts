import { Component, OnInit } from "@angular/core";
import { Author, AuthorService } from "@core/data/users";

@Component({
	selector    : "app-about",
	templateUrl : "./about.component.html",
	styleUrls   : [ "./about.component.scss" ],
})
export class AboutComponent implements OnInit {

	public author: Author = new Author();

	constructor (private service: AuthorService) {
	}

	ngOnInit () {
		this.getAuthor();
	}

	/**
	 * Get the author (aka me) information.
	 */
	private getAuthor() {
		this.service.findOne()
				.subscribe((result: Author) => {
					this.author = result;
				});
	}
}
