import { Component, OnInit } from "@angular/core";
import { PageTitleService } from "@theme/components/page-title/page-title.service";

@Component({
	selector    : "app-admin-dashboard",
	templateUrl : "./dashboard.component.html",
	styleUrls   : [ "./dashboard.component.scss" ],
})
export class DashboardComponent implements OnInit {

	constructor ( private pageTitle: PageTitleService ) {}

	ngOnInit () {
		this.pageTitle.setTitle("Dashboard");
	}

}
