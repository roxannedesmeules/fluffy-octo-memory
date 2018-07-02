import { Component, OnInit } from "@angular/core";
import { FormBuilder, FormGroup, Validators } from "@angular/forms";
import { CommunicationService } from "@core/data/communications";
import { ErrorResponse } from "@core/data/error-response.model";

@Component({
	selector    : "app-contact",
	templateUrl : "./contact.component.html",
	styleUrls   : [ "./contact.component.scss" ],
})
export class ContactComponent implements OnInit {

	public success: boolean = false;
	public loading: boolean = false;
	public submitted: boolean = false;

	public form: FormGroup;

	constructor (private _builder: FormBuilder, private service: CommunicationService) {
	}

	ngOnInit () {
		this._buildForm();
	}

	/**
	 * This method will create the FormGroup to be used in the page, with all possible validators. It will be called
	 * during the initialization of the component.
	 *
	 * @private
	 */
	private _buildForm () {
		this.form = this._builder.group({
			name    : this._builder.control("", [ Validators.required, ]),
			email   : this._builder.control("", [ Validators.required, Validators.email ]),
			subject : this._builder.control(""),
			message : this._builder.control("", [ Validators.required, ]),
		});
	}

	public isInvalid ( attrName: string ): boolean {
		return (this.form.get(attrName).invalid && (this.form.get(attrName).touched || this.submitted));
	}

	/**
	 * This method will be called when submitting the form, it will make the call to the service to create the message.
	 */
	public saveMessage () {
		this.success = false;
		this.submitted = true;

		if (this.form.invalid) {
			return;
		}

		this.loading = true;

		this.service
			.create(this.form.getRawValue())
			.subscribe(
					() => {
						this.loading   = false;
						this.success   = true;
						this.submitted = false;

						setTimeout(() => { this.success = false; }, 10000);

						this.form.reset();
					},
					( err: ErrorResponse ) => {
						this.loading   = false;
						this.success   = false;
						this.submitted = false;

						console.log(err);
					},
			);
	}
}
