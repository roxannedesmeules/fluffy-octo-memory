import { Pipe, PipeTransform } from "@angular/core";

@Pipe({
	name : "verbalBoolean",
})
export class VerbalBooleanPipe implements PipeTransform {

	transform ( value: boolean | number ): string {
		switch (value) {
			case 1 :
				// no break;
			case true :
				return "Yes";

			case 0 :
				// no break;
			case false :
				// no break;
			default :
				return "No";
		}
	}

}
