export class ErrorResponse {
	public code: number;
	public shortMessage: string;
	public message: string;
	public form_error: any;

	constructor (model: any = null) {
		if (model) {
			this.code         = model.code;
			this.shortMessage = model.shortMessage;
			this.message      = model.message;
			this.form_error   = model.error;
		}
	}
}
