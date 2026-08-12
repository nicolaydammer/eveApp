<?php

namespace App\Domain\Infrastructure\Esi\Enums;

enum Scope: string
{
    case PublicData = 'publicData';
    case CalendarRespondCalendarEvents = 'esi-calendar.respond_calendar_events.v1';
    case CalendarReadCalendarEvents = 'esi-calendar.read_calendar_events.v1';
    case LocationReadLocation = 'esi-location.read_location.v1';
    case LocationReadShipType = 'esi-location.read_ship_type.v1';
    case MailOrganizeMail = 'esi-mail.organize_mail.v1';
    case MailReadMail = 'esi-mail.read_mail.v1';
    case MailSendMail = 'esi-mail.send_mail.v1';
    case SkillsReadSkills = 'esi-skills.read_skills.v1';
    case SkillsReadSkillqueue = 'esi-skills.read_skillqueue.v1';
    case WalletReadCharacterWallet = 'esi-wallet.read_character_wallet.v1';
    case WalletReadCorporationWallet = 'esi-wallet.read_corporation_wallet.v1';
    case SearchSearchStructures = 'esi-search.search_structures.v1';
    case ClonesReadClones = 'esi-clones.read_clones.v1';
    case CharactersReadContacts = 'esi-characters.read_contacts.v1';
    case UniverseReadStructures = 'esi-universe.read_structures.v1';
    case KillmailsReadKillmails = 'esi-killmails.read_killmails.v1';
    case CorporationsReadCorporationMembership = 'esi-corporations.read_corporation_membership.v1';
    case AssetsReadAssets = 'esi-assets.read_assets.v1';
    case PlanetsManagePlanets = 'esi-planets.manage_planets.v1';
    case FleetsReadFleet = 'esi-fleets.read_fleet.v1';
    case FleetsWriteFleet = 'esi-fleets.write_fleet.v1';
    case UiOpenWindow = 'esi-ui.open_window.v1';
    case UiWriteWaypoint = 'esi-ui.write_waypoint.v1';
    case CharactersWriteContacts = 'esi-characters.write_contacts.v1';
    case FittingsReadFittings = 'esi-fittings.read_fittings.v1';
    case FittingsWriteFittings = 'esi-fittings.write_fittings.v1';
    case MarketsStructureMarkets = 'esi-markets.structure_markets.v1';
    case CorporationsReadStructures = 'esi-corporations.read_structures.v1';
    case CharactersReadLoyalty = 'esi-characters.read_loyalty.v1';
    case CharactersReadChatChannels = 'esi-characters.read_chat_channels.v1';
    case CharactersReadMedals = 'esi-characters.read_medals.v1';
    case CharactersReadStandings = 'esi-characters.read_standings.v1';
    case CharactersReadAgentsResearch = 'esi-characters.read_agents_research.v1';
    case IndustryReadCharacterJobs = 'esi-industry.read_character_jobs.v1';
    case MarketsReadCharacterOrders = 'esi-markets.read_character_orders.v1';
    case CharactersReadBlueprints = 'esi-characters.read_blueprints.v1';
    case CharactersReadCorporationRoles = 'esi-characters.read_corporation_roles.v1';
    case LocationReadOnline = 'esi-location.read_online.v1';
    case ContractsReadCharacterContracts = 'esi-contracts.read_character_contracts.v1';
    case ClonesReadImplants = 'esi-clones.read_implants.v1';
    case CharactersReadFatigue = 'esi-characters.read_fatigue.v1';
    case KillmailsReadCorporationKillmails = 'esi-killmails.read_corporation_killmails.v1';
    case CorporationsTrackMembers = 'esi-corporations.track_members.v1';
    case WalletReadCorporationWallets = 'esi-wallet.read_corporation_wallets.v1';
    case CharactersReadNotifications = 'esi-characters.read_notifications.v1';
    case CorporationsReadDivisions = 'esi-corporations.read_divisions.v1';
    case CorporationsReadContacts = 'esi-corporations.read_contacts.v1';
    case AssetsReadCorporationAssets = 'esi-assets.read_corporation_assets.v1';
    case CorporationsReadTitles = 'esi-corporations.read_titles.v1';
    case CorporationsReadBlueprints = 'esi-corporations.read_blueprints.v1';
    case ContractsReadCorporationContracts = 'esi-contracts.read_corporation_contracts.v1';
    case CorporationsReadStandings = 'esi-corporations.read_standings.v1';
    case CorporationsReadStarbases = 'esi-corporations.read_starbases.v1';
    case IndustryReadCorporationJobs = 'esi-industry.read_corporation_jobs.v1';
    case MarketsReadCorporationOrders = 'esi-markets.read_corporation_orders.v1';
    case CorporationsReadContainerLogs = 'esi-corporations.read_container_logs.v1';
    case IndustryReadCharacterMining = 'esi-industry.read_character_mining.v1';
    case IndustryReadCorporationMining = 'esi-industry.read_corporation_mining.v1';
    case PlanetsReadCustomsOffices = 'esi-planets.read_customs_offices.v1';
    case CorporationsReadFacilities = 'esi-corporations.read_facilities.v1';
    case CorporationsReadMedals = 'esi-corporations.read_medals.v1';
    case CharactersReadTitles = 'esi-characters.read_titles.v1';
    case AlliancesReadContacts = 'esi-alliances.read_contacts.v1';
    case CharactersReadFwStats = 'esi-characters.read_fw_stats.v1';
    case CorporationsReadFwStats = 'esi-corporations.read_fw_stats.v1';
    case CorporationsReadProjects = 'esi-corporations.read_projects.v1';
    case CorporationsReadFreelanceJobs = 'esi-corporations.read_freelance_jobs.v1';
    case CharactersReadFreelanceJobs = 'esi-characters.read_freelance_jobs.v1';
    case StructuresReadCorporation = 'esi-structures.read_corporation.v1';
    case StructuresReadCharacter = 'esi-structures.read_character.v1';
    case ActivitiesReadCharacter = 'esi-activities.read_character.v1';
    case AccessReadLists = 'esi-access.read_lists.v1';

    public static function toArray(): array
    {
        return array_map(
            fn(self $scope) => $scope->value,
            self::cases()
        );
    }
}
