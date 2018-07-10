import { Component, OnInit } from "@angular/core";
import { PageTitleService } from "@theme/components/page-title/page-title.service";

@Component({
	selector : "app-communication",
	template : `<router-outlet></router-outlet>`,
	styles   : [],
})
export class CommunicationComponent implements OnInit {

	constructor ( private pageTitle: PageTitleService ) {}

	ngOnInit () {
		this.pageTitle.setTitle("Messages");
	}

}
