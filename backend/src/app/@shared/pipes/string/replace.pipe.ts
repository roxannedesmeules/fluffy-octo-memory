import { Pipe, PipeTransform } from "@angular/core";

@Pipe({
	name : "replace",
})
export class ReplacePipe implements PipeTransform {

	transform ( string: string, params: any ): any {
		let result = string;

		for (const key in params) {
			if (!params.hasOwnProperty(key)) {
				continue;
			}

			result = result.replace(key, params[ key ]);
		}

		return result;
	}

}
