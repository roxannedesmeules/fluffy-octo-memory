import { Pipe, PipeTransform } from "@angular/core";

@Pipe({
	name : "atIndexOf",
})
export class AtIndexOfPipe implements PipeTransform {

	transform (search: any, list: Array<any>, compareIndex: string, valueIndex: string): any {
		let result = '';

		list.forEach((val, idx) => {
			if (val[ compareIndex ] === search) {
				result = val[ valueIndex ];
			}
		});

		return result;
	}

}
