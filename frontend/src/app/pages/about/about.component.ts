import { Component, OnInit, TemplateRef, ViewChild } from "@angular/core";
import { Meta, Title } from "@angular/platform-browser";
import { Author, AuthorService } from "@core/data/users";

@Component({
	selector    : "app-about",
	templateUrl : "./about.component.html",
	styleUrls   : [ "./about.component.scss" ],
})
export class AboutComponent implements OnInit {

	public author: Author = new Author();

	@ViewChild('metadataTranslation') metadataTranslation: TemplateRef<any>;

	constructor (private title: Title,
				 private meta: Meta,
				 private service: AuthorService) {
	}

	ngOnInit () {
		this.setMetadata();
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

	private setMetadata() {
		const nodes = this.metadataTranslation.createEmbeddedView({}).rootNodes;

		this.title.setTitle(nodes[ 1 ].innerText);
		this.meta.updateTag({ name: "description", content: nodes[ 3 ].innerText }, "name='description'");
	}
}
