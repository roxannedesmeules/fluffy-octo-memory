import { Component, OnInit } from "@angular/core";
import { Router } from "@angular/router";

@Component({
	selector    : "app-widget-search",
	templateUrl : "./search.component.html",
	styleUrls   : [ "./search.component.scss" ],
})
export class SearchComponent implements OnInit {

	public search;

	constructor ( private router: Router ) {
	}

	ngOnInit () {
	}

	searchFor ( event ) {
		if (event === null || event.key === "Enter") {
			const params = {
				queryParams : {
					"search"   : this.search,
					"page"     : 0,
					"per-page" : 10,
				},
			};

			//  navigate to the blog list with the search value as query param
			this.router.navigate([ "/blog" ], params);

			//  reset the searched value
			this.search = "";
		}
	}
}
