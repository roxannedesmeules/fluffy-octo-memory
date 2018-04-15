import { AfterViewInit, Component, ElementRef, forwardRef, Input, OnChanges, ViewChild } from "@angular/core";
import { ControlValueAccessor, FormControl, NG_VALUE_ACCESSOR } from "@angular/forms";

export const CUSTOM_INPUT_CONTROL_VALUE_ACCESSOR: any = {
	provide     : NG_VALUE_ACCESSOR,
	useExisting : forwardRef(() => FileInputComponent),
	multi       : true,
};

@Component({
	selector    : "app-file-input",
	templateUrl : "./file-input.component.html",
	styleUrls   : [ "./file-input.component.scss" ],
	providers   : [ CUSTOM_INPUT_CONTROL_VALUE_ACCESSOR ],
})
export class FileInputComponent implements ControlValueAccessor, AfterViewInit, OnChanges {

	// current form control input. helpful in validating and accessing form control
	@Input()
	public c: FormControl = new FormControl();

	// get reference to the input element
	@ViewChild("input")
	public inputRef: ElementRef;

	// The internal data model for form control value access
	private innerValue: any = "";

	constructor () {}

	ngOnChanges () {}

	ngAfterViewInit () {
		this.c.valueChanges
			.subscribe(() => {
				// get reference to the input element
				if (this.c.value === "" || this.c.value === null || this.c.value === undefined) {
					this.innerValue = "";
					this.inputRef.nativeElement.value = "";
				}
			});
	}

	//get accessor
	get value (): any {
		return this.innerValue;
	};

	//set accessor including call the onchange callback
	set value ( v: any ) {
		if (v !== this.innerValue) {
			this.innerValue = v;
		}
	}

	public updateFile ( $event ) {
		this.innerValue = $event.target.files[ 0 ];

		// propagate value into form control using control value accessor interface
		this.propagateChange(this.innerValue);
	}

	// propagate changes into the custom form control
	propagateChange = ( _: any ) => {};

	// From ControlValueAccessor interface
	writeValue ( value: any ) {
		this.innerValue = value;
	}

	// From ControlValueAccessor interface
	registerOnChange ( fn: any ) {
		this.propagateChange = fn;
	}

	// From ControlValueAccessor interface
	registerOnTouched ( fn: any ) {

	}
}
