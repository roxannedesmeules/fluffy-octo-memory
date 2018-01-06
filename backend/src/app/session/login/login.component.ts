import { Component, OnInit } from "@angular/core";
import { FormBuilder, FormGroup, Validators } from "@angular/forms";

@Component({
	selector    : "ngx-login",
	templateUrl : "./login.component.html",
	styleUrls   : [ "./login.component.scss" ],
})
export class LoginComponent implements OnInit {

	errors: string[]   = [];
	messages: string[] = [];
	submitted: boolean = false;

	public form: FormGroup;

	constructor (private _builder: FormBuilder, private _service: any) { }

	ngOnInit () {
		this._createForm();
	}

	/**
	 * Build login form group
	 *
	 * @private
	 */
	private _createForm () {
		this.form = this._builder.group({
			username : this._builder.control("", [ Validators.required ]),
			password : this._builder.control("", [ Validators.required ]),
		});
	}

	/**
	 *
	 */
	public login () {
		this._reset();

		this.submitted = true;

		this._service.login();
	}

	/**
	 * Reset errors and messages
	 *
	 * @private
	 */
	private _reset () {
		this.errors   = [];
		this.messages = [];
	}
}
