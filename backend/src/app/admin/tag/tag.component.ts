import { Component, OnInit } from "@angular/core";
import { PageTitleService } from "@theme/components/page-title/page-title.service";

@Component({
	selector : "app-tag",
	template : `<router-outlet></router-outlet>`,
})
export class TagComponent implements OnInit {

	constructor ( private pageTitle: PageTitleService ) {}

	ngOnInit () {
		this.pageTitle.setTitle("Tags");
	}
}
