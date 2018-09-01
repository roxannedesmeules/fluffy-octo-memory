import { CategoryCount } from "@core/data/categories/category-count.model";
import { Category } from "@core/data/categories/category.model";

describe("CategoryModel", () => {
    let model: Category;

    beforeEach(() => {
        model = new Category({
            id   : 1,
            name : "This is a test",
            slug : "this-is-a-test",
        });
    });

    afterEach(() => {
        model = null;
    });

    describe("#getUrl", () => {
        it("should return the URL of the category", () => {
            const expected = `/blog/${model.slug}`;

            expect(model.getUrl()).toEqual(expected);
        });
    });

    describe("#setPostCount", () => {
        it("should update the Post count to 5", () => {
            const expected = 5;
            const counts   = [
                new CategoryCount({ id : 1, count : 5 }),
                new CategoryCount({ id : 2, count : 3 }),
                new CategoryCount({ id : 3, count : 10 }),
            ];

            model.setPostCount(counts);

            expect(model.postCount).toEqual(expected);
        });

        it("should not update the Post count", () => {
            const expected = 0;
            const counts   = [
                new CategoryCount({ id : 2, count : 3 }),
                new CategoryCount({ id : 3, count : 10 }),
            ];

            model.setPostCount(counts);

            expect(model.postCount).toEqual(expected);
        });

        it("should update the Post count to 5, then stay the same if the count isn't found", () => {
            const expected = 5;
            let counts     = [
                new CategoryCount({ id : 1, count : 5 }),
                new CategoryCount({ id : 2, count : 3 }),
                new CategoryCount({ id : 3, count : 10 }),
            ];

            model.setPostCount(counts);

            expect(model.postCount).toEqual(expected);

            counts = [
                new CategoryCount({ id : 2, count : 3 }),
                new CategoryCount({ id : 3, count : 10 }),
            ];

            model.setPostCount(counts);

            expect(model.postCount).toEqual(expected);
        });
    });
});