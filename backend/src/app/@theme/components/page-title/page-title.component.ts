import { Component, OnInit } from "@angular/core";
import { PageTitleService } from "@theme/components/page-title/page-title.service";

@Component({
	selector    : "app-layout-page-title",
	templateUrl : "./page-title.component.html",
	styleUrls   : [ "./page-title.component.scss" ],
})
export class PageTitleComponent implements OnInit {

	public title: string;

	constructor ( private pageTitle: PageTitleService ) {}

	ngOnInit () {
		this.pageTitle.getSubject().subscribe((result: string) => { this.title = result; });
	}

}
