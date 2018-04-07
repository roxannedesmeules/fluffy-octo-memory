import { Inject, Injectable } from "@angular/core";
import { ToastrService } from "ngx-toastr";

@Injectable()
export class LoggerService {
	protected  static CONFIGS:any = {
		closeButton       : true,
		autoDismiss       : false,
		positionClass     : "toast-top-right",
		preventDuplicates : true,
	};

	private _toastr: ToastrService;

	constructor ( @Inject(ToastrService) toastr: ToastrService ) {
		this._toastr  = toastr;
	}

	public error ( message: string, title?: string) {
		this._toastr.error(message, title, LoggerService.CONFIGS);
	}

	public info ( message: string, title?: string) {
		this._toastr.info(message, title, LoggerService.CONFIGS);
	}

	public success ( message: string, title?: string) {
		this._toastr.success(message, title, LoggerService.CONFIGS);
	}

	public show ( message: string, title?: string) {
		this._toastr.show(message, title, LoggerService.CONFIGS);
	}

	public warning ( message: string, title?: string) {
		this._toastr.warning(message, title, LoggerService.CONFIGS);
	}
}
