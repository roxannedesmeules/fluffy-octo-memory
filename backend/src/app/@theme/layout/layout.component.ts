import { Component, OnInit } from "@angular/core";
import { NavigationEnd, NavigationStart, Router } from "@angular/router";
import { NgxSpinnerService } from "ngx-spinner";

@Component({
	selector    : "app-layout",
	templateUrl : "./layout.component.html",
	styleUrls   : [ "./layout.component.scss" ],
})
export class LayoutComponent implements OnInit {

	public isShrinked: boolean = false;

	constructor (private router: Router, private spinner: NgxSpinnerService) {
	}

	ngOnInit () {
		this.router.events.subscribe(event => {
			if (event instanceof NavigationStart) {
				this.spinner.show();
			}

			if (event instanceof NavigationEnd) {
				this.spinner.hide();
			}
		});
	}

}
