export class ErrorResponse {
    public code: number;
    public shortMessage: string;
    public message: string;
    public form_error: any;

    constructor(model: any = null) {
        if (!model) {
            return;
        }

        this.code = model.code;

        if (model.error) {
            this.shortMessage = model.error.message || "";
            this.message      = model.error.message || "";
            this.form_error   = model.error || [];
        }
    }
}
