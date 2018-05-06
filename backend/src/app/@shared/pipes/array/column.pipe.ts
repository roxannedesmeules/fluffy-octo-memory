import { Pipe, PipeTransform } from "@angular/core";

@Pipe({
	name : "column",
})
export class ColumnPipe implements PipeTransform {

	transform ( list: any[], column: string, asString: boolean = true ): string | string[] {
		const properties = column.split(".");
		const result     = [];

		list.forEach(( item ) => {
			let temp = item;

			properties.forEach((property, idx) => {
				if (temp.hasOwnProperty(property)) {
					temp = temp[ property ];

					if (properties.length === (idx + 1)) {
						result.push(temp);
					}
				}
			});
		});

		if (asString) {
			return result.join(", ");
		}

		return result;
	}

}
