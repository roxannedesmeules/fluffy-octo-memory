export class ErrorResponse {
	public code: number;
	public shortMessage: string;
	public message: string;

	//  TODO    update to correctly match errors from forms
	constructor (model: any = null) {
		if (model) {
			this.code         = model.code;
			this.shortMessage = model.shortMessage;
			this.message      = model.message;
		}
	}
}
