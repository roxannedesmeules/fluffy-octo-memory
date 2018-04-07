import { Pipe, PipeTransform } from "@angular/core";

@Pipe({
	name : "column",
})
export class ColumnPipe implements PipeTransform {

	transform ( list: any[], column: string, asString: boolean = true ): string | string[] {
		const result = [];

		list.forEach(( val ) => {
			if (val.hasOwnProperty(column)) {
				result.push(val[ column ]);
			}
		});

		if (asString) {
			return result.join(", ");
		}

		return result;
	}

}
