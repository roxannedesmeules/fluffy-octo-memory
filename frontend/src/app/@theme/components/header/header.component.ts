import { Component, Inject, LOCALE_ID, OnInit } from "@angular/core";

@Component({
	selector    : "app-layout-header",
	templateUrl : "./header.component.html",
	styleUrls   : [ "./header.component.scss" ],
})
export class HeaderComponent implements OnInit {

	constructor (@Inject(LOCALE_ID) protected localeId: string) {
	}

	ngOnInit () {
	}

	public isCurrent ( lang: string ): boolean {
		return (this.localeId === lang);
	}
}
