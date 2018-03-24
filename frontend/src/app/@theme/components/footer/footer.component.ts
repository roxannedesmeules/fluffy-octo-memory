import { Component, OnInit } from "@angular/core";
import { environment } from "../../../../environments/environment";

@Component({
	selector    : "app-layout-footer",
	templateUrl : "./footer.component.html",
	styleUrls   : [ "./footer.component.scss" ],
})
export class FooterComponent implements OnInit {

	public year = 2018;
	public socialMedia = environment.socialMedia;

	constructor () {
	}

	ngOnInit () {
		this._setCurrentYear();
	}

	private _setCurrentYear() {
		this.year = (new Date()).getFullYear();
	}
}
