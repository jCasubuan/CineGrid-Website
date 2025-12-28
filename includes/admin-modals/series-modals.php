<!-- Add Series Modal -->
     <div class="modal fade" id="addSeriesModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-white"><i class="bi bi-plus-circle me-2"></i>Add New Series</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addSeriesForm">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Series Title *</label>
                                <input type="text" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Year Range *</label>
                                <input type="text" class="form-control" placeholder="2008-2013" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Genre *</label>
                                <select class="form-select" required>
                                    <option value="">Select Genre</option>
                                    <option>Action</option>
                                    <option>Comedy</option>
                                    <option>Drama</option>
                                    <option>Crime</option>
                                    <option>Fantasy</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Seasons *</label>
                                <input type="number" class="form-control" min="1" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Episodes *</label>
                                <input type="number" class="form-control" min="1" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Creator *</label>
                            <input type="text" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Synopsis *</label>
                            <textarea class="form-control" rows="4" required></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Rating (IMDb)</label>
                                <input type="number" class="form-control" step="0.1" min="0" max="10">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-select">
                                    <option value="ongoing">Ongoing</option>
                                    <option value="completed">Completed</option>
                                    <option value="upcoming">Upcoming</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" form="addSeriesForm" class="btn btn-primary">Add Series</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Series Modal -->
     <div class="modal fade" id="editSeriesModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-pencil me-2"></i>Edit Series</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editSeriesForm">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Series Title *</label>
                                <input type="text" class="form-control" value="Breaking Bad" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-select">
                                    <option value="ongoing">Ongoing</option>
                                    <option value="completed" selected>Completed</option>
                                    <option value="upcoming">Upcoming</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Synopsis *</label>
                            <textarea class="form-control" rows="4" required>A chemistry teacher turns to crime...</textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" form="editSeriesForm" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </div>
    </div>