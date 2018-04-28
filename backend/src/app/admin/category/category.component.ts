import { Component, OnInit } from "@angular/core";
import { PageTitleService } from "@theme/components/page-title/page-title.service";

@Component({
	selector : "app-category",
	template : "<router-outlet></router-outlet>"
})
export class CategoryComponent implements OnInit {

	constructor ( private pageTitle: PageTitleService ) {}

	ngOnInit () {
		this.pageTitle.setTitle("Categories");
	}
}
