import { Component, OnInit } from "@angular/core";
import { AnalyticsService } from "@core/utils/analytics.service";
import { ToasterConfig } from "angular2-toaster";

import "style-loader!angular2-toaster/toaster.css";

@Component({
	selector    : "ngx-app",
	templateUrl : "./app.component.html",
})
export class AppComponent implements OnInit {

	public toastConfig: ToasterConfig;

	constructor ( private analytics: AnalyticsService ) {
	}

	ngOnInit (): void {
		this.analytics.trackPageViews();

		this.toastConfig = new ToasterConfig({
			positionClass   : "toast-top-full-width",
			tapToDismiss    : true,
			showCloseButton : false,
		});
	}
}
