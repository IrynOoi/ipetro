-- 1. CLEANUP (Optional: Drops existing tables to start fresh)
DROP TABLE IF EXISTS public.inspection_methods CASCADE;
DROP TABLE IF EXISTS public.inspection_part CASCADE;
DROP TABLE IF EXISTS public.report CASCADE;
DROP TABLE IF EXISTS public.inspection CASCADE;
DROP TABLE IF EXISTS public.equipment_part CASCADE;
DROP TABLE IF EXISTS public.equipment CASCADE;
DROP TABLE IF EXISTS public.users CASCADE;

-- 2. CREATE TABLES

-- Table: users
CREATE TABLE public.users (
    user_id SERIAL PRIMARY KEY,
    username character varying(50) NOT NULL UNIQUE,
    password character varying(255) NOT NULL,
    role character varying(20) NOT NULL CHECK (role IN ('admin', 'user')),
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    full_name character varying(100),
    profile_picture character varying(500)
);

-- Table: equipment
CREATE TABLE public.equipment (
    equipment_id SERIAL PRIMARY KEY,
    equipment_no character varying(50) NOT NULL UNIQUE,
    pmt_no character varying(50) NOT NULL,
    equipment_desc character varying(255),
    is_active boolean DEFAULT true,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    image_url character varying(1024),
    design_code character varying(100)
);

-- Table: equipment_part
CREATE TABLE public.equipment_part (
    part_id SERIAL PRIMARY KEY,
    equipment_id integer NOT NULL,
    part_name character varying(100) NOT NULL,
    is_active boolean DEFAULT true,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_ep_equipment FOREIGN KEY (equipment_id) REFERENCES public.equipment(equipment_id) ON UPDATE CASCADE,
    UNIQUE (equipment_id, part_name)
);

-- Table: inspection
CREATE TABLE public.inspection (
    inspection_id bigserial PRIMARY KEY,
    equipment_id integer NOT NULL,
    inspected_at timestamp without time zone NOT NULL,
    inspected_by character varying(100) NOT NULL,
    check_type character varying(50),
    location character varying(100),
    notes text,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_insp_equipment FOREIGN KEY (equipment_id) REFERENCES public.equipment(equipment_id) ON UPDATE CASCADE
);

-- Table: inspection_part
CREATE TABLE public.inspection_part (
    inspection_id bigint NOT NULL,
    part_id integer NOT NULL,
    part_name character varying(100) NOT NULL,
    phase character varying(50),
    fluid character varying(100),
    type character varying(100),
    spec character varying(100),
    grade character varying(100),
    insulation character varying(100),
    design_temp numeric(8,2),
    design_pressure numeric(8,2),
    operating_temp numeric(8,2),
    operating_pressure numeric(8,2),
    condition_note text,
    current_risk_rating character varying(50),
    corrosion_group text,
    design_code character varying(100),
    PRIMARY KEY (inspection_id, part_id),
    CONSTRAINT fk_insp_part_inspection FOREIGN KEY (inspection_id) REFERENCES public.inspection(inspection_id) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_insp_part_part FOREIGN KEY (part_id) REFERENCES public.equipment_part(part_id) ON UPDATE CASCADE
);

-- Table: inspection_methods
CREATE TABLE public.inspection_methods (
    method_id SERIAL PRIMARY KEY,
    inspection_id bigint NOT NULL,
    method_name character varying(255) NOT NULL,
    coverage text,
    damage_mechanism character varying(255),
    CONSTRAINT fk_im_inspection FOREIGN KEY (inspection_id) REFERENCES public.inspection(inspection_id) ON DELETE CASCADE
);

-- Table: report
CREATE TABLE public.report (
    report_id bigserial PRIMARY KEY,
    inspection_id bigint NOT NULL,
    report_no character varying(50) NOT NULL UNIQUE,
    report_type character varying(50),
    file_path character varying(500),
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_report_inspection FOREIGN KEY (inspection_id) REFERENCES public.inspection(inspection_id) ON UPDATE CASCADE ON DELETE CASCADE
);

-- 3. INSERT DATA

-- Users
INSERT INTO public.users (user_id, username, password, role, created_at, full_name, profile_picture) VALUES
(1, 'admin', 'admin123', 'admin', '2025-11-20 21:31:51.888', 'coachbolasepak', '/uploads/profiles/profile-1765093349331-470272486.jpg'),
(2, 'raziqhadif', 'user123', 'user', '2025-11-20 21:31:51.888', NULL, '/uploads/profiles/profile-1765092906429-110923510.png');

-- Equipment
INSERT INTO public.equipment (equipment_id, equipment_no, pmt_no, equipment_desc, is_active, created_at, image_url, design_code) VALUES
(1, 'V-001', 'MLK PMT 10101', 'Air Receiver', true, '2025-11-09 12:39:48', 'https://i.imgur.com/g1f6tst.png', 'ASME VIII DIV 1'),
(2, 'V-002', 'MLK PMT 10102', 'Expansion Tank', true, '2025-11-09 12:39:48', NULL, 'ASME SEC. VIII DIV. 1 2010 EDITION'),
(3, 'V-003', 'MLK PMT 10103', 'Condensate Vessel', true, '2025-11-09 12:39:48', NULL, NULL),
(4, 'V-004', 'MLK PMT 10104', 'Hot Water System', true, '2025-11-09 12:39:48', NULL, NULL),
(5, 'V-005', 'MLK PMT 10105', 'Absorber for Neutralization of Acid Gases', true, '2025-11-09 12:39:48', NULL, NULL),
(6, 'V-006', 'MLK PMT 10106', 'Thermal Deaerator', true, '2025-11-09 12:39:48', NULL, NULL),
(7, 'H-001', 'MLK PMT 10107', 'Cooling of Steam- Gas Mix at The Exit of the Reactor', true, '2025-11-09 12:39:48', '/uploads/image-1763644377143-759814067.png', 'ASME VIII DIV 1'),
(8, 'H-002', 'MLK PMT 10108', 'Gas Scrubber Cooler', true, '2025-11-09 12:39:48', NULL, NULL),
(9, 'H-003', 'MLK PMT 10109', 'Cooling of Water on Irrigation of An Absorber', true, '2025-11-09 12:39:48', NULL, NULL),
(10, 'H-004', 'MLK PMT 10110', 'Reflux Condensor of Drying Tower', true, '2025-11-09 12:39:48', NULL, NULL);

-- Equipment Parts
INSERT INTO public.equipment_part (part_id, equipment_id, part_name, is_active, created_at) VALUES
(1, 1, 'Top Head', true, '2025-11-09 12:40:00'),
(2, 1, 'Shell', true, '2025-11-09 12:40:00'),
(3, 1, 'Bottom Head', true, '2025-11-09 12:40:00'),
(4, 2, 'Top Head', true, '2025-11-09 12:40:00'),
(5, 2, 'Shell', true, '2025-11-09 12:40:00'),
(6, 2, 'Bottom Head', true, '2025-11-09 12:40:00'),
(7, 3, 'Top Head', true, '2025-11-09 12:40:00'),
(8, 3, 'Shell', true, '2025-11-09 12:40:00'),
(9, 3, 'Bottom Head', true, '2025-11-09 12:40:00'),
(10, 4, 'Head', true, '2025-11-09 12:40:00'),
(11, 4, 'Shell', true, '2025-11-09 12:40:00'),
(13, 5, 'Top Channel', true, '2025-11-09 12:40:00'),
(14, 5, 'Shell', true, '2025-11-09 12:40:00'),
(15, 5, 'Bottom Channel', true, '2025-11-09 12:40:00'),
(16, 6, 'Head', true, '2025-11-09 12:40:00'),
(17, 6, 'Shell', true, '2025-11-09 12:40:00'),
(19, 7, 'Channel', true, '2025-11-09 12:40:00'),
(20, 7, 'Shell', true, '2025-11-09 12:40:00'),
(21, 7, 'Tube Bundle', true, '2025-11-09 12:40:00'),
(22, 8, 'Channel', true, '2025-11-09 12:40:00'),
(23, 8, 'Shell', true, '2025-11-09 12:40:00'),
(24, 8, 'Tube Bundle', true, '2025-11-09 12:40:00'),
(25, 9, 'Channel', true, '2025-11-09 12:40:00'),
(26, 9, 'Shell', true, '2025-11-09 12:40:00'),
(27, 9, 'Tube Bundle', true, '2025-11-09 12:40:00'),
(28, 10, 'Channel', true, '2025-11-09 12:40:00'),
(29, 10, 'Shell', true, '2025-11-09 12:40:00'),
(30, 10, 'Tube Bundle', true, '2025-11-09 12:40:00');

-- Inspections
INSERT INTO public.inspection (inspection_id, equipment_id, inspected_at, inspected_by, check_type, location, notes, created_at) VALUES
(1, 7, '2025-11-17 18:35:10.175', 'N/A', NULL, NULL, NULL, '2025-11-17 18:35:10.175'),
(9, 7, '2025-11-18 02:15:31.545', 'N/A', NULL, NULL, NULL, '2025-11-18 02:15:31.547'),
(10, 7, '2025-11-21 02:24:22.964', 'N/A', NULL, NULL, NULL, '2025-11-21 02:24:22.968'),
(11, 2, '2025-11-21 03:14:37.114', 'admin', NULL, NULL, NULL, '2025-11-21 03:14:37.116'),
(12, 7, '2025-12-04 16:42:03.308', 'admin', NULL, NULL, NULL, '2025-12-04 16:42:03.308'),
(13, 7, '2025-12-07 15:39:53.036', 'admin', NULL, NULL, NULL, '2025-12-07 15:39:53.037');

-- Inspection Methods
INSERT INTO public.inspection_methods (method_id, inspection_id, method_name, coverage, damage_mechanism) VALUES
(1, 9, 'UTTM (Correction Factor)', '100% of TML Location', 'General Corrosion'),
(2, 9, 'Visual Inspection', 'External Visual Inspection (100% Coverage)', 'Atmospheric Corrosion'),
(3, 10, 'UTTM (Correction Factor)', '100% of TML Location', 'General Corrosion'),
(4, 10, 'Visual Inspection', 'External Visual Inspection (100% Coverage)', 'Atmospheric Corrosion'),
(5, 11, 'UTTM (Correction Factor)', '100% of TML Location', 'General Corrosion'),
(6, 11, 'Visual Inspection', 'External Visual Inspection (100% Coverage)', 'Atmospheric Corrosion'),
(7, 12, 'UTTM (Correction Factor)', '100% of TML Location', 'General Corrosion'),
(8, 12, 'Visual Inspection', 'External Visual Inspection (100% Coverage)', 'Atmospheric Corrosion'),
(9, 13, 'UTTM (Correction Factor)', '100% of TML Location', 'General Corrosion'),
(10, 13, 'Visual Inspection', 'External Visual Inspection (100% Coverage)', 'Atmospheric Corrosion');

-- Inspection Parts (Details)
INSERT INTO public.inspection_part (inspection_id, part_id, part_name, phase, fluid, type, spec, grade, insulation, design_temp, design_pressure, operating_temp, operating_pressure, condition_note, current_risk_rating, corrosion_group, design_code) VALUES
(1, 19, 'Channel', 'Gas', 'STEAM', 'Carbon Steel', 'SA-516', '70', '100', 160.00, 0.50, 150.00, 0.40, '{"ut_reading":null,"visual_finding":null}', NULL, NULL, NULL),
(1, 20, 'Shell', 'Gas', 'STEAM', 'Carbon Steel', 'SA-516', '70', '100', 160.00, 0.50, 150.00, 0.40, '{"ut_reading":null,"visual_finding":null}', NULL, NULL, NULL),
(1, 21, 'Tube Bundle', 'Gas', 'STEAM', 'Alloy Steel', 'SA-213', 'T11', '100', 160.00, 0.50, 150.00, 0.40, '{"ut_reading":null,"visual_finding":null}', NULL, NULL, NULL),
(9, 19, 'Channel', 'Gas', 'Vent Gas', 'CS', 'SA-516', '70L', '100', 500.00, 0.50, 150.00, 0.40, '{"ut_reading":null,"visual_finding":null}', 'LOW', E'Internal Shell\nGeneral Corrosion\n\nExternal\nATMOSPHERIC CORROSION', NULL),
(9, 20, 'Shell', 'Gas', 'Vent Gas', 'CS', 'SA-516', '70L', '100', 500.00, 0.50, 150.00, 0.40, '{"ut_reading":null,"visual_finding":null}', 'LOW', E'Internal Shell\nGeneral Corrosion\n\nExternal\nATMOSPHERIC CORROSION', NULL),
(9, 21, 'Tube Bundle', 'Gas', 'Vent Gas', 'SS', 'SA-240', '304', '100', 500.00, 0.50, 150.00, 0.40, '{"ut_reading":null,"visual_finding":null}', 'LOW', E'Internal Shell\nGeneral Corrosion\n\nExternal\nATMOSPHERIC CORROSION', NULL),
(10, 19, 'Channel', 'Gas', 'VENT GAS', 'CS', 'SA-516', '70L', '100', 500.00, 0.50, 450.00, 0.40, '{"ut_reading":null,"visual_finding":null}', 'LOW', E'Internal Shell\nGeneral Corrosion\n\nExternal\nATMOSPHERIC CORROSION', NULL),
(10, 20, 'Shell', 'Gas', 'VENT GAS', 'CS', 'SA-516', '70L', '100', 500.00, 0.50, 450.00, 0.40, '{"ut_reading":null,"visual_finding":null}', 'LOW', E'Internal Shell\nGeneral Corrosion\n\nExternal\nATMOSPHERIC CORROSION', NULL),
(10, 21, 'Tube Bundle', 'Gas', 'VENT GAS', 'AS', 'SA-213', 'T22', '100', 500.00, 0.50, 450.00, 0.40, '{"ut_reading":null,"visual_finding":null}', 'LOW', E'Internal Shell\nGeneral Corrosion\n\nExternal\nATMOSPHERIC CORROSION', NULL),
(11, 4, 'Top Head', 'Liquid', 'WATER', 'CS', 'SA-516', '70L', 'P.T.F.E LINED', 60.00, 1.00, 50.00, 0.80, '{"ut_reading":null,"visual_finding":null}', 'LOW', E'Internal Shell\nGeneral Corrosion\n\nExternal\nATMOSPHERIC CORROSION', NULL),
(11, 5, 'Shell', 'Liquid', 'WATER', 'CS', 'SA-516', '70L', 'P.T.F.E LINED', 60.00, 1.00, 50.00, 0.80, '{"ut_reading":null,"visual_finding":null}', 'LOW', E'Internal Shell\nGeneral Corrosion\n\nExternal\nATMOSPHERIC CORROSION', NULL),
(11, 6, 'Bottom Head', 'Liquid', 'WATER', 'CS', 'SA-516', '70L', 'P.T.F.E LINED', 60.00, 1.00, 50.00, 0.80, '{"ut_reading":null,"visual_finding":null}', 'LOW', E'Internal Shell\nGeneral Corrosion\n\nExternal\nATMOSPHERIC CORROSION', NULL),
(12, 19, 'Channel', 'Liquid', 'Cooling Water', 'CS', 'SA-516', '70', 'None', 2.00, 2.00, 2.00, 0.55, '{"ut_reading":null,"visual_finding":null}', 'LOW', E'Internal Shell\nGeneral Corrosion\n\nExternal\nATMOSPHERIC CORROSION', NULL),
(12, 20, 'Shell', 'Liquid', 'Cooling Water', 'CS', 'SA-516', '70', 'None', 2.00, 2.00, 2.00, 0.55, '{"ut_reading":null,"visual_finding":null}', 'LOW', E'Internal Shell\nGeneral Corrosion\n\nExternal\nATMOSPHERIC CORROSION', NULL),
(12, 21, 'Tube Bundle', 'Liquid', 'Cooling Water', 'SS', 'SA-249', 'TP304', 'None', 2.00, 2.00, 2.00, 0.55, '{"ut_reading":null,"visual_finding":null}', 'LOW', E'Internal Shell\nGeneral Corrosion\n\nExternal\nATMOSPHERIC CORROSION', NULL),
(13, 19, 'Channel', 'Gas', 'Vent Gas', 'CS', 'SA-516', '70', 'NO', 120.00, 0.14, 100.00, 0.10, '{"ut_reading":null,"visual_finding":null}', 'LOW', E'Internal Shell\nGeneral Corrosion\n\nExternal\nATMOSPHERIC CORROSION', NULL),
(13, 20, 'Shell', 'Gas', 'Vent Gas', 'CS', 'SA-516', '70', 'NO', 120.00, 0.14, 100.00, 0.10, '{"ut_reading":null,"visual_finding":null}', 'LOW', E'Internal Shell\nGeneral Corrosion\n\nExternal\nATMOSPHERIC CORROSION', NULL),
(13, 21, 'Tube Bundle', 'Gas', 'Vent Gas', 'SS', 'SA-240', '304L', 'NO', 120.00, 0.14, 100.00, 0.10, '{"ut_reading":null,"visual_finding":null}', 'LOW', E'Internal Shell\nGeneral Corrosion\n\nExternal\nATMOSPHERIC CORROSION', NULL);

-- 4. RESET SEQUENCE VALUES
-- This ensures new inserts do not conflict with existing IDs
SELECT setval('public.equipment_equipment_id_seq', 10, true);
SELECT setval('public.equipment_part_part_id_seq', 30, true);
SELECT setval('public.inspection_inspection_id_seq', 13, true);
SELECT setval('public.inspection_methods_method_id_seq', 10, true);
SELECT setval('public.report_report_id_seq', 1, false);
SELECT setval('public.users_user_id_seq', 2, true);