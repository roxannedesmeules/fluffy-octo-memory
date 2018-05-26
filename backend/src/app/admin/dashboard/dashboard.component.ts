import { Component, OnInit } from "@angular/core";
import { PageTitleService } from "@theme/components/page-title/page-title.service";

@Component({
	selector    : "app-admin-dashboard",
	templateUrl : "./dashboard.component.html",
	styleUrls   : [ "./dashboard.component.scss" ],
})
export class DashboardComponent implements OnInit {

	public counts = [
		{ color : "bg-red", icon : "far fa-file-alt", title : "Published<br/>Post", count : 4, rate : "60%" },
		{ color : "bg-green", icon : "far fa-folder-open", title : "Active<br/>Categories", count : 20, rate : "50%" },
		{ color : "bg-orange", icon : "fas fa-hashtag", title : "Active<br/>Tags", count : 5, rate : "42%" },
	];

	constructor ( private pageTitle: PageTitleService ) {}

	ngOnInit () {
		this.pageTitle.setTitle("Dashboard");
	}

}
