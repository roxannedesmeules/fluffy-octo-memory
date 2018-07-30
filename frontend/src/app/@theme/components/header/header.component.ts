import { Component, Inject, LOCALE_ID, OnInit } from "@angular/core";
import { animate, state, style, transition, trigger } from "@angular/animations";
import { Router } from "@angular/router";

@Component({
	selector    : "app-layout-header",
	templateUrl : "./header.component.html",
	styleUrls   : [ "./header.component.scss" ],
	animations  : [
		trigger("visibilityChanged", [
			state("open", style({ opacity : 1, display : "block", 'z-index' : 9999 })),
			state("close", style({ opacity : 0, display : "none" })),
			transition("open => close", animate("600ms ease-out")),
			transition("close => open", animate("300ms ease-in")),
		]),
	],
})
export class HeaderComponent implements OnInit {

	public searchValue: string = "";
	public searchState: string = "close";

	constructor ( @Inject(LOCALE_ID) protected localeId: string,
				  private router: Router) {
	}

	ngOnInit () {
	}

	public isCurrent ( lang: string ): boolean {
		return (this.localeId === lang);
	}

	public toggleSearch ( action: string ) {
		switch (action) {
			case "open":
				this.searchState = "open";
				break;

			case "close":
				this.searchState = "close";
				break;
		}

		this.searchValue = "";
	}

	public searchFor (event = null) {
		if (event === null || event.key === "Enter") {
			const params = {
				queryParams : {
					"page"     : 0,
					"per-page" : 10,
				},
			};

			if (this.searchValue !== "") {
				params.queryParams[ "search" ] = this.searchValue;
			}

			//  navigate to the blog list with the search value as query param
			this.router.navigate([ "/blog" ], params);

			//  reset the searched value
			this.toggleSearch('close');
		}
	}
}
