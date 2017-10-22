import { Component } from "@angular/core";
import { ToastData, ToastOptions, ToastyService } from "ng2-toasty";

@Component({
	selector    : "app-logger",
	templateUrl : "./logger.component.html",
	styleUrls   : [ "./logger.component.scss" ],
})
export class LoggerComponent {

	defaultOptions = {
		showClose : true,
		timeout   : 5000,
		theme     : "default",
		position  : "top-right",
		onAdd     : (toast: ToastData) => { console.log(`toast ${toast.id} has been added!`); },
		onRemove  : (toast: ToastData) => { console.log(`toast ${toast.id} has been removed!`); },
	};

	constructor (private _toasty: ToastyService) {}

	error (title, msg) {
		this._toasty.error(this._fullOptions(title, msg));
	}

	info (title, msg) {
		this._toasty.info(this._fullOptions(title, msg));
	}

	success (title, msg) {
		this._toasty.success(this._fullOptions(title, msg));
	}

	wait (title, msg) {
		this._toasty.wait(this._fullOptions(title, msg));
	}

	warning (title, msg) {
		this._toasty.warning(this._fullOptions(title, msg));
	}

	private _fullOptions (title, msg): ToastOptions {
		return <ToastOptions>Object.assign({ title : title, message : msg }, this.defaultOptions);
	}
}
