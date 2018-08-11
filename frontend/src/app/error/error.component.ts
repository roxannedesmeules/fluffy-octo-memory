import { Component, OnInit, TemplateRef, ViewChild } from "@angular/core";
import { Meta, Title } from "@angular/platform-browser";
import { Router } from "@angular/router";

@Component({
	selector    : "app-error",
	templateUrl : "./error.component.html",
	styleUrls   : [ "./error.component.scss" ],
})
export class ErrorComponent implements OnInit {

	public code: number = 404;

	@ViewChild('metadataTranslation') metadataTranslation: TemplateRef<any>;

	constructor (private title: Title,
				 private meta: Meta,
				 private router: Router) {
	}

	ngOnInit () {
		this.setCode();
		this.setMetadata();
	}

	/**
	 * This method will check if the error code is the code passed in parameter.
	 *
	 * @param {number} code
	 * @return {boolean}
	 */
	public is (code: number): boolean {
		return (this.code === code);
	}

	/**
	 * This method will define the error code depending on the URL of the router.
	 */
	private setCode() {
		//  depending on URL define the error code.
		switch (this.router.url) {
			case "/server-failed" :
				this.code = 500;
				break;

			default :
				this.code = 404;
				break;
		}
	}

	/**
	 * This method will set the page title depending on the right code and language.
	 */
	private setMetadata() {
		const nodes = this.metadataTranslation.createEmbeddedView({}).rootNodes;

		switch (this.code) {
			case 500:
				this.title.setTitle(nodes[1].innerText);
				break;
			case 404:
				this.title.setTitle(nodes[3].innerText);
				break;
		}
	}
}
