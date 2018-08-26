import { PostFilters } from "@core/data/posts/post.filters";

describe("Post Filters", () => {
    let filters: PostFilters;

    //  set up for each test
    beforeEach(() => {
        filters = new PostFilters();
    });

    //  clean up after each test
    afterEach(() => {
        filters = null;
    });

    it("should set a filter with the passed value", () => {
        filters.set("featured", 1);

        expect(filters.featured).toEqual(1);
    });

    it("should set update pagination filters with properly structure argument", () => {
        filters.setPagination({ currentPage : 2, perPage : 12 });

        expect(filters.pageNumber).toEqual(2);
        expect(filters.perPage).toEqual(12);
    });

    it("should not set update pagination filters with poorly structure argument", () => {
        filters.setPagination({ pageNumber : 2, "per-page" : 12 });

        expect(filters.pageNumber).not.toEqual(2);
        expect(filters.perPage).not.toEqual(12);
    });

    it("should reset all filters to their original values", () => {
        filters.set("category", "unit-testing");
        filters.set("tag", "test");
        filters.setPagination({ currentPage : 2, perPage : 12 });

        expect(filters.category).toEqual("unit-testing");
        expect(filters.tag).toEqual("test");
        expect(filters.pageNumber).toEqual(2);
        expect(filters.perPage).toEqual(12);

        filters.reset();

        expect(filters.category).toEqual("");
        expect(filters.tag).toEqual("");
        expect(filters.pageNumber).toEqual(PostFilters.DEFAULT_CURRENT_PAGE);
        expect(filters.perPage).toEqual(PostFilters.DEFAULT_PER_PAGE);
    });

    it("should return true if filter has a value", () => {
        filters.set("category", "unit-testing");

        expect(filters.isSet("category")).toBeTruthy();
    });

    it("should return false if filter doesn't have a value", () => {
        filters.set("featured", -1);

        expect(filters.isSet("category")).toBeFalsy();
    });

    it("should return an object with all filters that are set", () => {
        filters.set("category", "unit-testing");

        expect(filters.formatRequest()).toEqual(jasmine.objectContaining({
            params : {
                category   : "unit-testing",
                page       : PostFilters.DEFAULT_CURRENT_PAGE,
                "per-page" : PostFilters.DEFAULT_PER_PAGE,
            },
        }));
    });
});