import { Component, OnInit } from "@angular/core";
import { Router } from "@angular/router";

@Component({
	selector    : "app-error",
	templateUrl : "./error.component.html",
	styleUrls   : [ "./error.component.scss" ],
})
export class ErrorComponent implements OnInit {

	public code: number = 404;

	constructor (private router: Router) {
	}

	ngOnInit () {
		//  depending on URL define the error code.
		switch (this.router.url) {
			case "/server-failed" :
				this.code = 500;
				break;

			default :
				this.code = 404;
				break;
		}
	}

	/**
	 * This method will check if the error code is the code passed in parameter.
	 *
	 * @param {number} code
	 * @return {boolean}
	 */
	is (code: number): boolean {
		return (this.code === code);
	}
}
